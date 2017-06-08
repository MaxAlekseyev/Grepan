<?php

abstract class QM_NewPostV2_Block_Checkout_Onepage_OptionsAbstract extends Mage_Checkout_Block_Onepage_Shipping_Method_Available
{
    protected $_selectedCityId;
    protected $_lastCustomerOrder;
    protected $_api;

    public function __construct()
    {
        $this->_api=Mage::helper('qm_newpostv2/api');
    }

    public abstract function getCode();

    protected function _getLastCustomerOrder()
    {
        if (!$this->_lastCustomerOrder) {
            $this->_lastCustomerOrder = Mage::helper('qmcheckout')->getLastCustomerOrder();
        }
        return $this->_lastCustomerOrder;
    }

    protected function _getLastCustomerOrderCityId()
    {
        if ($order = $this->_getLastCustomerOrder()) {
            $address = $order->getShippingAddress();
            $city = Mage::getModel('qm_newpostv2/city')->loadByLocaleDescription($address->getCity());
            return $city->getCityId();
        }
    }

    protected function _getSelectedCityId()
    {
        if (!$this->_selectedCityId) {
            $address = Mage::getModel('checkout/cart')->getQuote()->getShippingAddress();
            $lastOrderCityId = $this->_getLastCustomerOrderCityId();
            if (!$address->getCity() && $lastOrderCityId) {
                $this->_selectedCityId = $lastOrderCityId;
            } else {
                $city = Mage::getModel('qm_newpostv2/city')->loadByLocaleDescription($address->getCity());
                $this->_selectedCityId = $city->getId();
            }
        }
        return $this->_selectedCityId;
    }

    protected function _getCityCollection()
    {
        return Mage::getModel('qm_newpostv2/city')->getCollection();
    }

    protected function _getCityJson()
    {
        return $this->_getJson($this->_getCityCollection(), $this->_getSelectedCityId());
    }

    protected function _getJson($collection, $selected)
    {
        $data = array();
        $data['collection'] = array();
        $data['selected'] = (int)$selected;

        foreach($collection as $item) {
            $data['collection'][$item->getId()] = $item->getLocaleDescription();
        }

        return json_encode($data, JSON_FORCE_OBJECT);
    }

    /**
     * @return VarienObject(name, json)
     */
    protected abstract function _getJsonData();

    public function _toHtml() {
        if ($this->getTemplate()) {
            return parent::_toHtml();
        }

        $html = '';

        $items = $this->_getJsonData();

        foreach ($items as $item) {
            $html .= '<div id="data_' . $item->getName() . '_' . $this->getCode() . '" style="display: none">';
            $html .= $item->getJson();
            $html .= '</div>';
        }

        return $html;
    }

}