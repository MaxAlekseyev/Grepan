<?php

class QM_NewPostV2_Model_Resource_Area_Waybill extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('qm_newpostv2/waybill');
    }
} 