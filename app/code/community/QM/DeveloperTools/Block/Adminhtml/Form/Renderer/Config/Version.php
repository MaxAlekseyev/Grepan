<?php
class QM_DeveloperTools_Block_Adminhtml_Form_Renderer_Config_Version extends Mage_Adminhtml_Block_System_Config_Form_Field {

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        $value = (string) Mage::getConfig()->getNode('modules/QM_DeveloperTools/version');
        return $value;
    }
}
