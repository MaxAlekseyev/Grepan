<?php
class QM_LayeredNavigation_Model_Filter extends Mage_Core_Model_Abstract
{
    public function _construct()
    {    
        $this->_init('qm_layerednavigation/filter');
    }
    
    public function getDisplayTypeString()
    {
        $helper = Mage::helper('qm_layerednavigation');
        $options = array();
        
        if ($this->getBackendType() == 'decimal') {
            $options = $helper->getDecimalDisplayTypes();
        } else {
            $options = $helper->getDisplayTypes();
        }
        
        return $options[$this->getDisplayType()];
    }

    /**
     * Return default if Chooser Type selected but it disabled
     */
    public function getDisplayType()
    {
        if (in_array(parent::getDisplayType(),array(5,6)) && !Mage::helper('qm_layerednavigation')->isColorSwitherEnabled() ) {
            $this->setDisplayType(0);
        }

        return parent::getDisplayType();
    }
}