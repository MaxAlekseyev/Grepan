<?php

class QM_Switcher_Block_Adminhtml_System_Attribute_After_Image extends QM_Switcher_Block_Adminhtml_System_Attribute_After_Options
{
    /**
     * @return string
     */
    public function getButtonLabel()
    {
        return Mage::helper('qm_switcher')->__('Configure Images');
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'image';
    }

    /**
     * @return mixed|string
     */
    public function getSubmitUrl()
    {
        return $this->getUrl('adminhtml/switcher/saveImage');
    }
}
