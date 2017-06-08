<?php

class QM_DeveloperTools_Model_Resource_Conflict_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
    public function _construct() {
        parent::_construct();
        $this->_init('qm_devtools/conflict');
    }
}