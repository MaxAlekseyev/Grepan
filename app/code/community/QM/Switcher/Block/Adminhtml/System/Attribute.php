<?php

class QM_Switcher_Block_Adminhtml_System_Attribute extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        /** @var QM_Switcher_Block_Adminhtml_System_Attribute_Renderer $block */
        $block = Mage::app()->getLayout()->createBlock('qm_switcher/adminhtml_system_attribute_renderer');
        $block->setElement($element);
        return $block->toHtml();
    }
}
