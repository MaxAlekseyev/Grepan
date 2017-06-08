<?php
class QM_LayeredNavigation_Model_Range extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('qm_layerednavigation/range');
    }
}