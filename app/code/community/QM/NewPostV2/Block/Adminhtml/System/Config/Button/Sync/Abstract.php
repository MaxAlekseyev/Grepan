<?php

abstract class QM_NewPostV2_Block_Adminhtml_System_Config_Button_Sync_Abstract extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected $_method = '';

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        $url = Mage::helper('adminhtml')->getUrl('qm_newpost_v2_admin/adminhtml_sync/' . $this->_method);

        $html = $this->getLayout()->createBlock('adminhtml/widget_button')
                     ->setType('button')
                     ->setClass('scalable')
                     ->setLabel('Run Now !')
                     ->setOnClick("setLocation('" . $url . "')")
                     ->toHtml();

        return $html;
    }
}