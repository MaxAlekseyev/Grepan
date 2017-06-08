<?php

class QM_OrderTracker_Block_Adminhtml_System_Config_Button_Sync extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected $_method = '';

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        $url = Mage::helper('adminhtml')->getUrl('adminhtml/tracking/syncAllOrders');

        $html = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setType('button')
            ->setClass('scalable')
            ->setLabel(Mage::helper('qm_ordertracker')->__('Run'))
            ->setOnClick("setLocation('" . $url . "')")
            ->toHtml();

        return $html;
    }
}