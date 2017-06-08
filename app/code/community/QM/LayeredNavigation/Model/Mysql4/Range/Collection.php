<?php
class QM_LayeredNavigation_Model_Mysql4_Range_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('qm_layerednavigation/range');
    }
}