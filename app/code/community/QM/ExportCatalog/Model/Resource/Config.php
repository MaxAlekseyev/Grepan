<?php

class QM_ExportCatalog_Model_Resource_Config extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('qm_exportcatalog/table_config', 'id');
    }
}