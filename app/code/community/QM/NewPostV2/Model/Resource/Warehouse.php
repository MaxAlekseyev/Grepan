<?php

class QM_NewPostV2_Model_Resource_Warehouse extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('qm_newpostv2/table_warehouse', 'id');
    }

} 