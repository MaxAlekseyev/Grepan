<?php

abstract class QM_Metatager_Model_Indexer_Abstract extends Mage_Index_Model_Indexer_Abstract
{
    protected        $_ruleHelper       = null;
    protected        $_helper           = null;
    protected static $_reindexAllRuns   = false;
    protected        $_saveHandledItems = array();

    public function _construct()
    {
        $this->_ruleHelper = Mage::helper('qm_metatager/rule');
        $this->_helper     = Mage::helper('qm_metatager');

        parent::_construct();
    }

    public function getName()
    {
        return $this->_helper->__($this->_name);
    }

    public function getDescription()
    {
        return $this->_helper->__($this->_description);
    }

    protected abstract function _isAddOnSave($storeId);

    protected function _registerEvent(Mage_Index_Model_Event $event)
    {
        $dataObj = $event->getDataObject();

        if (!$this->_isAddOnSave($dataObj->getStoreId())) {
            return;
        }

        if ($event->getType() == Mage_Index_Model_Event::TYPE_SAVE) {
            $event->addNewData($this->_indexerId . '_' . $this->_entityName . '_id', $dataObj->getId());
            $event->addNewData($this->_indexerId . '_store_id', $dataObj->getStoreId());
        } elseif ($event->getType() == Mage_Index_Model_Event::TYPE_MASS_ACTION) {
            $event->addNewData($this->_indexerId . '_' . $this->_entityName . '_ids', $dataObj->getProductIds());
            $event->addNewData($this->_indexerId . '_store_id', $dataObj->getStoreId());
        }
    }

    protected function _processEvent(Mage_Index_Model_Event $event)
    {
        $data = $event->getNewData();

        if (!empty($data[$this->_indexerId . '_' . $this->_entityName . '_id'])) {
            $this->_generateModel->generateOne(
                $data[$this->_indexerId . '_' . $this->_entityName . '_id'],
                $data[$this->_indexerId . '_store_id']);
        } elseif (!empty($data[$this->_indexerId . '_' . $this->_entityName . '_ids'])) {
            $this->_generateModel->generateMany(
                $data[$this->_indexerId . '_' . $this->_entityName . '_ids'],
                $data[$this->_indexerId . '_store_id']);
        }
    }

    public function matchEvent(Mage_Index_Model_Event $event)
    {
        $data = $event->getNewData();

        if (isset($data[$this->_indexerId])) {
            return $data[$this->_indexerId];
        }

        $entity = $event->getEntity();

        if ($entity != $this->_entity) {
            return false;
        }

        $event->addNewData($this->_indexerId, true);

        return true;
    }

    public function reindexAll()
    {
        $this->_generateModel->reindexAll();
    }
} 