<?php

/**
 * Banner resource model
 *
 * @category    QM
 * @package     QM_Banners
 *
 */
class QM_Banners_Model_Resource_Banner extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     *
     */
    public function _construct()
    {
        $this->_init('qm_banners/banner', 'entity_id');
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @access public
     * @param int $bannerId
     * @return array
     *
     */
    public function lookupStoreIds($bannerId)
    {
        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
            ->from($this->getTable('qm_banners/banner_store'), 'store_id')
            ->where('banner_id = ?', (int)$bannerId);
        return $adapter->fetchCol($select);
    }

    /**
     * Perform operations after object load
     *
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return QM_Banners_Model_Resource_Banner
     *
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
        }
        return parent::_afterLoad($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param QM_Banners_Model_Banner $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($object->getStoreId()) {
            $storeIds = array(Mage_Core_Model_App::ADMIN_STORE_ID, (int)$object->getStoreId());
            $select->join(
                array('banners_banner_store' => $this->getTable('qm_banners/banner_store')),
                $this->getMainTable() . '.entity_id = banners_banner_store.banner_id',
                array()
            )
            ->where('banners_banner_store.store_id IN (?)', $storeIds)
            ->order('banners_banner_store.store_id DESC')
            ->limit(1);
        }
        return $select;
    }

    /**
     * Assign banner to store views
     *
     * @access protected
     * @param Mage_Core_Model_Abstract $object
     * @return QM_Banners_Model_Resource_Banner
     *
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table  = $this->getTable('qm_banners/banner_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = array(
                'banner_id = ?' => (int) $object->getId(),
                'store_id IN (?)' => $delete
            );
            $this->_getWriteAdapter()->delete($table, $where);
        }
        if ($insert) {
            $data = array();
            foreach ($insert as $storeId) {
                $data[] = array(
                    'banner_id'  => (int) $object->getId(),
                    'store_id' => (int) $storeId
                );
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
        return parent::_afterSave($object);
    }

}
