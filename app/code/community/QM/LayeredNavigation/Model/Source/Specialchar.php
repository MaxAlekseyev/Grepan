<?php
class QM_LayeredNavigation_Model_Source_Specialchar extends Varien_Object
{
    public function toOptionArray()
    {
        $hlp = Mage::helper('qm_layerednavigation');
        return array(
        	array('value' => '_', 'label' => $hlp->__('_')),
            array('value' => '-', 'label' => $hlp->__('-')),
        );
    }
    
}