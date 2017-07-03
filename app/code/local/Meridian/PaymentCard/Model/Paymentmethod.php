<?php

class Meridian_PaymentCard_Model_Paymentmethod extends Mage_Payment_Model_Method_Abstract
{
    protected $_canUseInternal = true;
    protected $_canUseForMultishipping = true;
    protected $_canAuthorize              = true;
    protected $_canCapture = true;
    protected $_code  = 'paymentcard';

    public function assignData($data)
    {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->getInfoInstance();
        $info->setCheckNo($data->getCheckNo())
            ->setCheckDate($data->getCheckDate());
        return $this;
    }

}