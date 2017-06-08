<?php

class QM_NewPostV2_Block_Checkout_Onepage_OptionsWarehouseDoors extends QM_NewPostV2_Block_Checkout_Onepage_OptionsAbstract
{
    protected $_selectedWhId;

    public function getCode()
    {
        $api = $this->_api;
        return $api::WAREHOUSE_DOORS_RATE_CODE;
    }

    protected function _getLastCustomerOrderStreetId()
    {
        if ($order = $this->_getLastCustomerOrder()) {
            $address = $order->getShippingAddress();
            $street = Mage::getModel('qm_newpostv2/street')->loadByLocaleDescription($address->getStreet1());
            return $street->getId();
        }
    }

    protected function _getSelectedStreetId()
    {
        if (!$this->_selectedWhId) {
            $this->_selectedWhId = $this->_getLastCustomerOrderStreetId();
        }
        return $this->_selectedWhId;
    }

    protected  function _getStreetCollection()
    {
        $cityId = $this->_getSelectedCityId();

        return Mage::getModel('qm_newpostv2/street')->getCollection()->filterByCity($cityId);
    }

    protected function _getStreetJson($collection, $selected)
    {
        $data = array();
        $data['collection'] = array();
        $data['selected'] = (int)$selected;

        foreach($collection as $street) {
            $data['collection'][$street->getId()] = $street->getStreetsType() . ' ' . $street->getLocaleDescription();
        }

        return json_encode($data, JSON_FORCE_OBJECT);
    }

    protected function _getJsonData()
    {
        $data = array();

        $cityData = new Varien_Object();
        $cityData->setName('city');
        $cityData->setJson($this->_getCityJson());
        array_push($data, $cityData);

        $cityData = new Varien_Object();
        $cityData->setName('street');
        $cityData->setJson($this->_getStreetJson($this->_getStreetCollection(), $this->_getSelectedStreetId()));
        array_push($data, $cityData);

        return $data;
    }

}