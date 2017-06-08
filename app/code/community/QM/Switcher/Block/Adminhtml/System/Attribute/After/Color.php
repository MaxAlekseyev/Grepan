<?php

class QM_Switcher_Block_Adminhtml_System_Attribute_After_Color extends QM_Switcher_Block_Adminhtml_System_Attribute_After_Options
{
    /**
     * @return string
     */
    public function getButtonLabel()
    {
        return Mage::helper('qm_switcher')->__('Configure Colors');
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'color';
    }
}
