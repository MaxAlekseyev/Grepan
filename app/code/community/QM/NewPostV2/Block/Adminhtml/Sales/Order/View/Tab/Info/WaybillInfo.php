<?php


class QM_NewPostV2_Block_Adminhtml_Sales_Order_View_Tab_Info_WaybillInfo extends Mage_Adminhtml_Block_Template
{
    public function getOrder()
    {
        return Mage::registry('current_order');
    }

    protected function _getShippingMethod()
    {
        return $this->getOrder()->getShippingMethod();
    }

    public function isNewPostOrder()
    {
        $helper = Mage::helper('qm_newpostv2');

        return $helper->isWarehouseToWarehouseService($this->_getShippingMethod())
        || $helper->isDoorsToDoorsService($this->_getShippingMethod());
    }

    public function getWaybill()
    {
        return Mage::getModel('qm_newpostv2/waybill')->load($this->getOrder()->getId(), 'order_id');
    }

    public function isWaybillExists()
    {
        return $this->getWaybill()->getId();
    }

    public function getWaybillPrintUrl()
    {
        return Mage::helper('qm_newpostv2/api')->getWaybillPrintUrl($this->getWaybill());
    }
}