<?php

abstract class QM_Metatager_Model_Abstract extends Mage_Catalog_Model_Resource_Abstract
{
    protected $_ruleHelper;
    protected $_helper;

    protected function _construct()
    {
        parent::_construct();

        $this->_ruleHelper = Mage::helper('qm_metatager/rule');
        $this->_helper = Mage::helper('qm_metatager');

        $resource = Mage::getSingleton('core/resource');
        $this->setType($this->_type)
            ->setConnection(
                $resource->getConnection('catalog_read'),
                $resource->getConnection('catalog_write')
            );
    }

    abstract protected function _getGeneratedEntityIds($storeId);
    abstract protected function _isReindexOnlyActiveStore();
    abstract protected function _getEntityModel();

    protected function _getReindexAllEntityIds($storeId)
    {
        if ($this->_isReindexAll($storeId)) {
            return $this->_getEntityModel()->getCollection()->getAllIds();
        } else {
            return $this->_getGeneratedEntityIds($storeId);
        }
    }

    protected function _validateGeneratedData($data, $skipGenerated = false)
    {
        if (!count($data)) {
            return false;
        }

        if ($skipGenerated) {
            return true;
        }

        $emptyGenerated = true;

        foreach ($data as $productIdStoreId => $generated) {
            if (count($generated)) {
                $emptyGenerated = false;
            }
        }

        if ($emptyGenerated) {
            return false;
        }

        return true;
    }

    public function reindexAll()
    {
        $storeIds = array(0);

        $allStores = Mage::app()->getStores();
        foreach ($allStores as $store) {
            if (!$this->_isReindexOnlyActiveStore() || $store->getIsActive()) {
                $storeIds[] = $store->getId();
            }
        }

        foreach ($storeIds as $storeId) {
            $entityIds = $this->_getReindexAllEntityIds($storeId);

            $this->generateMany($entityIds, $storeId);
        }
    }
}