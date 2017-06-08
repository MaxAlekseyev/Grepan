<?php

class QM_Switcher_Block_Adminhtml_Helper_Image extends Varien_Data_Form_Element_Image
{
    /**
     * @return bool|string
     */
    protected function _getUrl()
    {
        $url = false;
        if ($this->getValue()) {
            /** @var QM_Switcher_Helper_Optimage $helper */
            $helper = Mage::helper('qm_switcher/optimage');
            $url = $helper->getImageBaseUrl().$this->getValue();
        }
        return $url;
    }
}
