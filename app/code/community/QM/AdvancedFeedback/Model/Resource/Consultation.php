<?php

class QM_AdvancedFeedback_Model_Resource_Consultation extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('qmadvfeedback/consultation', 'entity_id');
    }

    public function lookupStoreIds($consultationId)
    {
        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
            ->from($this->getTable('qmadvfeedback/consultation_store'), 'store_id')
            ->where('consultation_id = ?',(int)$consultationId);
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
                array('advancedfeedback_consultation_store' => $this->getTable('qmadvfeedback/consultation_store')),
                $this->getMainTable() . '.entity_id = advancedfeedback_consultation_store.consultation_id',
                array()
            )
            ->where('advancedfeedback_consultation_store.store_id IN (?)', $storeIds)
            ->order('advancedfeedback_consultation_store.store_id DESC')
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
        $table  = $this->getTable('qmadvfeedback/consultation_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = array(
                'consultation_id = ?' => (int) $object->getId(),
                'store_id IN (?)' => $delete
            );
            $this->_getWriteAdapter()->delete($table, $where);
        }
        if ($insert) {
            $data = array();
            foreach ($insert as $storeId) {
                $data[] = array(
                    'consultation_id'  => (int) $object->getId(),
                    'store_id' => (int) $storeId
                );
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
        return parent::_afterSave($object);
    }
}
