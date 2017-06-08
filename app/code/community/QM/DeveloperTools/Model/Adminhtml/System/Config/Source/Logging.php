<?php
class QM_DeveloperTools_Model_Adminhtml_System_Config_Source_Logging {

    public function toOptionArray() {
        return array(
            array('value' => 0, 'label' => Mage::helper('qm_devtools')->__('no logging')),
            array('value' => 1, 'label' => Mage::helper('qm_devtools')->__('log only denied access')),
            array('value' => 2, 'label' => Mage::helper('qm_devtools')->__('log all access')),
        );
    }
}
