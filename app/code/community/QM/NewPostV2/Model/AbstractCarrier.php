<?php

abstract class QM_NewPostV2_Model_AbstractCarrier
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
    const METHOD_CODE = 'qm_newpostv2';

    protected $_carrierCode;
    protected $_apiServiceType;
    protected $_helper;
    protected $_api;

    public function __construct()
    {
        $this->_helper = Mage::helper('qm_newpostv2');
        $this->_api    = Mage::helper('qm_newpostv2/api');
    }

    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        $result = Mage::getModel('shipping/rate_result');

        $result->append($this->_getMethod($request));

        return $result;
    }

    protected function _getMethod($request)
    {
        if (!Mage::getStoreConfig('carriers/' . $this->_carrierCode . '/active')) {
            return false;
        }

        $method = Mage::getModel('shipping/rate_result_method');

        $method->setCarrier($this->_carrierCode);
        $method->setMethod($this->_carrierCode);
        $method->setCarrierTitle(Mage::getStoreConfig('carriers/' . $this->_carrierCode . '/title'));
        $method->setMethodTitle(Mage::getStoreConfig('carriers/' . $this->_carrierCode . '/name'));

        if ($request->getFreeShipping() === true) {
            $shippingPrice = '0.00';
        } else {
            $shippingPrice = $this->_api->getShippingPrice($this->_apiServiceType);
        }

        $shippingPrice = $this->getFinalPriceWithHandlingFee($shippingPrice);

        $method->setCost($shippingPrice);
        $method->setPrice($shippingPrice);

        Mage::log($request->getFreeShipping());

        return $method;
    }

    public function getAllowedMethods()
    {
        return array(
            $this->_carrierCode => Mage::getStoreConfig('carriers/' . $this->_carrierCode . '/name')
        );
    }
}
