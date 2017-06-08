<?php

class QM_OrderTracker_Model_Observer extends Varien_Object
{
    protected $_service;
    protected $_helper;

    protected function _construct()
    {
        $this->_service = Mage::getSingleton('qm_ordertracker/service');
        $this->_helper = Mage::helper('qm_ordertracker');
    }

    public function shipmentSaved($observer)
    {
        if (!$this->_helper->isEnabled()) {
            return $this;
        }

        $shipment = $observer->getShipment();

        $this->_service->updateOrder($shipment->getOrder());
    }

    public function orderCreated($observer)
    {
        if (!$this->_helper->isEnabled()) {
            return $this;
        }

        $this->_service->updateOrder($observer->getOrder());
    }

    public function cronJob()
    {
        if (!$this->_helper->isEnabled()) {
            return $this;
        }

        $this->_service->updateAllOrders();
    }
}