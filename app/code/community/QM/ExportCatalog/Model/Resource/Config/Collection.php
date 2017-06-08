<?php

class QM_ExportCatalog_Model_Resource_Config_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('qm_exportcatalog/config');
    }
}