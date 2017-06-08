<?php

class QM_NewPostV2_Model_Observer
{
    public function saveShippingMethod($observer)
    {
        $request        = $observer->getEvent()->getRequest();
        $quote          = $observer->getEvent()->getQuote();
        $shippingMethod = $request->getParam('shipping_method');
        $params         = new Varien_Object($request->getParam('shipping_newpost', array()));
        $api            = Mage::helper('qm_newpostv2/api');

        switch ($shippingMethod) {
            case ($api::WAREHOUSE_WAREHOUSE_RATE_CODE . '_' . $api::WAREHOUSE_WAREHOUSE_RATE_CODE):
                $city      = Mage::getModel('qm_newpostv2/city')->load($params->getCityId());
                $warehouse = Mage::getModel('qm_newpostv2/warehouse')->load($params->getWarehouseId());

                if ($city->getId()) {
                    $quote->getShippingAddress()->setCity($city->getLocaleDescription());
                }

                if ($warehouse->getId()) {
                    $quote->getShippingAddress()->setStreet($warehouse->getLocaleDescription());
                }

                $quote->collectTotals()->save();
                break;
            case ($api::WAREHOUSE_DOORS_RATE_CODE . '_' . $api::WAREHOUSE_DOORS_RATE_CODE):
                $city   = Mage::getModel('qm_newpostv2/city')->load($params->getCityId());
                $street = Mage::getModel('qm_newpostv2/street')->load($params->getStreetId());

                if ($city->getId()) {
                    $quote->getShippingAddress()->setCity($city->getLocaleDescription());
                }

                if ($street->getId()) {
                    $quote->setStreet($street->getStreetsType() . ' ' . $street->getLocaleDescription() . ', ' . $params->getBuildingNum() . ', ' . $params->getApartmentNum());
                }

                $quote->collectTotals()->save();
                break;
        }
    }

    public function updateSessionOrderCity($observer)
    {
        $event   = $observer->getEvent();
        $request = $event->getRequest();
        $params  = new Varien_Object($request->getParam('shipping_newpost', array()));

        if ($params->getCityId()) {
            $city = Mage::getModel('qm_newpostv2/city')->load($params->getCityId());
            $event->getAddress()->setCity($city->getLocaleDescription());
        }
    }

    public function observeBeforeCollectQuote($observer)
    {
        $key = 'qm_newpostv2_quote_before_collect';

        if (!Mage::registry($key)) {
            Mage::register($key, $observer->getEvent()->getQuote());
        }
    }
} 