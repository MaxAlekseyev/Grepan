<?php

class QM_NewPostV2_Model_Waybill extends Mage_Core_Model_Abstract
{
    protected $_defaults;
    protected $_api;
    protected $_helper;
    protected $_order;
    protected $_currencyHelper;

    public function _construct()
    {
        parent::_construct();
        $this->_init('qm_newpostv2/waybill');

        $this->_api            = Mage::helper('qm_newpostv2/api');
        $this->_helper         = Mage::helper('qm_newpostv2');
        $this->_currencyHelper = Mage::helper('qm_newpostv2/currency');

        $this->_defaults = array(
            'payer_type'     => 'Recipient',
            'payment_method' => 'Cash',
            'cargo_type'     => 'Cargo',
            'seats_amount'   => 1
        );

        $this->_defaults['city_sender'] = $this->_helper->getCitySender();

        $this->_loadDefaults();
    }

    protected function _loadDefaults()
    {
        foreach ($this->_defaults as $key => $value) {
            $this->setData($key, $value);
        }
    }

    public function setRecipient(Varien_Object $recipient)
    {
        parent::setRecipient($recipient->getData('Ref'));
        parent::setContactRecipient($recipient->getData('ContactPerson/data/0/Ref'));
    }

    public function setSender(Varien_Object $recipient)
    {
        parent::setSender($recipient->getData('Ref'));
        parent::setContactSender($recipient->getData('ContactPerson/data/0/Ref'));
    }

    public function setRecipientAddress(Varien_Object $address)
    {
        parent::setRecipientAddress($address->getData('Ref'));
    }

    public function setSenderAddress(Varien_Object $address)
    {
        parent::setSenderAddress($address->getData('Ref'));
    }

    public function setOrder(Mage_Sales_Model_Order $order)
    {
        $this->setOrderId($order->getId());
        $this->_order = $order;
    }

    protected function _isCashOnDeliveryPayment($payment)
    {
        return $payment->getCode() == Mage::getSingleton('qm_newpostv2/payment_cashOnDelivery')->getCode();
    }

    public function setPayment(Mage_Payment_Model_Method_Abstract $payment)
    {
        if (!$this->_isCashOnDeliveryPayment($payment)) {
            return;
        }

        if (!$this->_order) {
            throw new Exception('Set Order Before');
        }

        $priceWithouShipping = $this->_order->getGrandTotal() - $this->_order->getShippingAmount();
        $this->setData('backward_delivery_data', array((object)array(
            'PayerType'        => $this->_helper->isSenderBackwardDeliveryPayer() ? 'Sender' : 'Recipient',
            'CargoType'        => 'Money',
            'RedeliveryString' => $this->_currencyHelper->convertCurrencyOut($priceWithouShipping)
        )));
    }

    private function _underscoreToCamelcase($str)
    {
        $str[0] = strtoupper($str[0]);

        $str = preg_replace_callback("/_[a-z]/", function ($match) {
            foreach ($match as $letter) {
                return strtoupper(str_replace('_', '', $letter));
            }

        }, $str);

        return $str;
    }

    /**
     * Formated names for new post
     */
    public function getRequestData()
    {
        $rData = array();

        foreach ($this->getData() as $key => $value) {
            $rData[$this->_underscoreToCamelcase($key)] = $value;
        }

        return $rData;
    }

} 