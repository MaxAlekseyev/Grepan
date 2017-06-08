<?php

class QM_NewPostV2_Model_Payment_CashOnDelivery extends Mage_Payment_Model_Method_Abstract
{
    protected $_code = 'qm_newpostv2_pay_on_delivery';

    public function isAvailable($quote = null)
    {
        if (!$quote || !parent::isAvailable($quote)) {
            return null;
        }
        $helper = Mage::helper('qm_newpostv2');
        $shippingMethod = $quote->getShippingAddress()->getShippingMethod();

        return $helper->isWarehouseToWarehouseService($shippingMethod)
            || $helper->isDoorsToDoorsService($shippingMethod);
    }
}