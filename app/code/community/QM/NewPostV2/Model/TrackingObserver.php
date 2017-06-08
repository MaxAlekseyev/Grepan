<?php

class QM_NewPostV2_Model_TrackingObserver extends Varien_Object
{
    protected $_helper;

    public function __construct()
    {
        $this->_helper = Mage::helper('qm_newpostv2');
    }

    public function _isNewPostOrder($order)
    {
        return $this->_helper->isWarehouseToWarehouseService($order->getShippingMethod())
        || $this->_helper->isDoorsToDoorsService($order->getShippingMethod());
    }

    protected function _getWaybill($order)
    {
        return Mage::getModel('qm_newpostv2/waybill')->load($order->getId(), 'order_id');
    }

    protected function _isWaybillCreated($order)
    {
        return $this->_getWaybill($order)->getId();
    }

    protected function _getTrackingData($order)
    {
        $key = 'tracking_' . $order->getId();

        if (!$this->getData($key)) {
            $waybill = Mage::getModel('qm_newpostv2/waybill')->load($order->getId(), 'order_id');

            $this->setData($key, Mage::helper('qm_newpostv2/api')->trackWaybill($waybill));
        }

        return $this->getData($key);
    }

    protected function _getOrderStatus($order)
    {
        return $this->_getTrackingData($order)->getData('StateName');
    }

    protected function _getOrderAddress($order)
    {
        return $this->_getTrackingData($order)->getData('AddressUA');
    }

    function collect($observer)
    {
        $order = $observer->getOrder();

        if (!$this->_isNewPostOrder($order)) {
            return;
        }

        $tracking = $observer->getTracking();
        $tracking->setIsSupported(true);

        if ($this->_isWaybillCreated($order)) {
            $tracking->setIsAvailable(true);
            $tracking->setStatus($this->_getOrderStatus($order));
            $tracking->setAddress($this->_getOrderAddress($order));
        } else {
            $tracking->setNotAvailableMessage($this->_helper->__('Tracking will be available after you create waybill'));
        }
    }
}