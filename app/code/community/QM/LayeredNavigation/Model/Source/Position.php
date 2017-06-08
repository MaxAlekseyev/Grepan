<?php
class QM_LayeredNavigation_Model_Source_Position extends Varien_Object
{
    public function toOptionArray()
    {
        $hlp = Mage::helper('qm_layerednavigation');
        return array(
            array('value' => 'left', 'label' => $hlp->__('Sidebar')),
            array('value' => 'top',  'label' => $hlp->__('Top')),
        );
    }
    
}