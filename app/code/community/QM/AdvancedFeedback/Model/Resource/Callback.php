<?php

class QM_AdvancedFeedback_Model_Resource_Callback extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('qmadvfeedback/callback', 'entity_id');
    }

    public function lookupStoreIds($callbackId)
    {
        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
            ->from($this->getTable('qmadvfeedback/callback_store'), 'store_id')
            ->where('callback_id = ?',(int)$callbackId);
        return $adapter->fetchCol($select);
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
        }
        return parent::_afterLoad($object);
    }

    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($object->getStoreId()) {
            $storeIds = array(Mage_Core_Model_App::ADMIN_STORE_ID, (int)$object->getStoreId());
            $select->join(
                array('advancedfeedback_callback_store' => $this->getTable('qmadvfeedback/callback_store')),
                $this->getMainTable() . '.entity_id = advancedfeedback_callback_store.callback_id',
                array()
            )
            ->where('advancedfeedback_callback_store.store_id IN (?)', $storeIds)
            ->order('advancedfeedback_callback_store.store_id DESC')
            ->limit(1);
        }
        return $select;
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table  = $this->getTable('qmadvfeedback/callback_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = array(
                'callback_id = ?' => (int) $object->getId(),
                'store_id IN (?)' => $delete
            );
            $this->_getWriteAdapter()->delete($table, $where);
        }
        if ($insert) {
            $data = array();
            foreach ($insert as $storeId) {
                $data[] = array(
                    'callback_id'  => (int) $object->getId(),
                    'store_id' => (int) $storeId
                );
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
        return parent::_afterSave($object);
    }
}
