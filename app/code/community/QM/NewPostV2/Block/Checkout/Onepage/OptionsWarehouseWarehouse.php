<?php

class QM_NewPostV2_Block_Checkout_Onepage_OptionsWarehouseWarehouse extends QM_NewPostV2_Block_Checkout_Onepage_OptionsAbstract
{
    protected $_selectedWhId;

    public function getCode()
    {
        $api = $this->_api;
        return $api::WAREHOUSE_WAREHOUSE_RATE_CODE;
    }

    protected function _getLastCustomerOrderWarehouseId()
    {
        if ($order = $this->_getLastCustomerOrder()) {
            $address = $order->getShippingAddress();
            $warehouse = Mage::getModel('qm_newpostv2/warehouse')->loadByLocaleDescription($address->getStreet1());
            return $warehouse->getId();
        }
    }

    protected function _getSelectedWarehouseId()
    {
        if (!$this->_selectedWhId) {
            $this->_selectedWhId = $this->_getLastCustomerOrderWarehouseId();
        }
        return $this->_selectedWhId;
    }

    protected  function _getWarehouseCollection()
    {
        $cityId = $this->_getSelectedCityId();
        return Mage::getModel('qm_newpostv2/warehouse')->getCollection()->filterByCity($cityId);
    }

    protected function _getJsonData()
    {
        $data = array();

        $cityData = new Varien_Object();
        $cityData->setName('city');
        $cityData->setJson($this->_getCityJson());
        array_push($data, $cityData);

        $cityData = new Varien_Object();
        $cityData->setName('warehouse');
        $cityData->setJson($this->_getJson($this->_getWarehouseCollection(), $this->_getSelectedWarehouseId()));
        array_push($data, $cityData);

        return $data;
    }

}