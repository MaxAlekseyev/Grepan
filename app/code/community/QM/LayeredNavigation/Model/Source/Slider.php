<?php
class QM_LayeredNavigation_Model_Source_Slider extends Varien_Object
{
    public function toOptionArray()
    {
        $hlp = Mage::helper('qm_layerednavigation');
        return array(
            array('value' => 0, 'label' => $hlp->__('Default')),
            array('value' => 1, 'label' => $hlp->__('With ranges')),
        );
    }
    
}