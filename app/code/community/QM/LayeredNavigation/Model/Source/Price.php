<?php
class QM_LayeredNavigation_Model_Source_Price extends Varien_Object
{
    public function toOptionArray()
    {
        $hlp = Mage::helper('qm_layerednavigation');
        return array(
            array('value' => QM_LayeredNavigation_Model_Catalog_Layer_Filter_Price::DT_DEFAULT,    'label' => $hlp->__('Default')),
            array('value' => QM_LayeredNavigation_Model_Catalog_Layer_Filter_Price::DT_DROPDOWN,   'label' => $hlp->__('Dropdown')),
            array('value' => QM_LayeredNavigation_Model_Catalog_Layer_Filter_Price::DT_FROMTO,     'label' => $hlp->__('From-To Only')),
            array('value' => QM_LayeredNavigation_Model_Catalog_Layer_Filter_Price::DT_SLIDER,     'label' => $hlp->__('Slider')),
        );
    }
    
}