<?php
class QM_DeveloperTools_Model_Resource_Conflict extends Mage_Core_Model_Resource_Db_Abstract {
    public function _construct() {
        $this->_init('qm_devtools/conflict', 'ec_id');
    }

    public function truncateTable() {
        $this->_getWriteAdapter()->delete($this->getMainTable(), "1=1");
    }
}