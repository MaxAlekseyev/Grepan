<?php


class QM_NewPostV2_Block_Adminhtml_Sales_Order_Shipment_Create_Form_NewPost extends Mage_Adminhtml_Block_Template
{
    protected function _getShippingMethod()
    {
        return $this->getShipment()->getOrder()->getShippingMethod();
    }

    public function isNewPostShipment()
    {
        $helper = Mage::helper('qm_newpostv2');

        return $helper->isWarehouseToWarehouseService($this->_getShippingMethod())
        || $helper->isDoorsToDoorsService($this->_getShippingMethod());
    }

    public function isCreateWaybillChecked()
    {
        return Mage::helper('qm_newpostv2')->createWaybillByDefault();
    }

    public function getDefaultDescription()
    {
        $description = '';
        $items = $this->getShipment()->getOrder()->getAllItems();

        foreach ($items as $item) {
            $description .= $item->getProduct()->getName();
            $description .= ' x ' . (int)$item->getQtyOrdered() . '.';
        }

        return $description;
    }
}