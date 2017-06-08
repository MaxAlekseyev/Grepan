<?php
class QM_LayeredNavigation_Model_Mysql4_Range extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('qm_layerednavigation/range', 'range_id');
    }
}