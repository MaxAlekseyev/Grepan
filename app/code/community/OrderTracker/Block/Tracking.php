<?php

class QM_OrderTracker_Block_Tracking extends Mage_Core_Block_Template
{
    protected $_tracking;
    protected $_helper;

    protected function _construct()
    {
        parent::_construct();

        $this->_tracking = Mage::getModel('qm_ordertracker/tracking')->load($this->_getOrder()->getId(), 'order_id');
        $this->_helper = Mage::helper('qm_ordertracker');
    }

    protected function _getOrder()
    {
        return Mage::registry('current_order');
    }

    public function isTrackingSupported()
    {
        return $this->_tracking->getIsSupported();
    }

    public function isTrackingAvailable()
    {
        return $this->_tracking->getIsAvailable();
    }

    public function getTrackingNotAvailableMessage()
    {
        if ($message = $this->_tracking->getNotAvailableMessage()) {
            return $message;
        }

        return $this->__('Tracking is not available now');
    }

    public function getStatus()
    {
        if ($status = $this->_tracking->getStatus()) {
            return $status;
        }

        return $this->__('Order status is not available');
    }

    public function getAddress()
    {
        if ($address = $this->_tracking->getAddress()) {
            return $address;
        }

        return $this->__('Address is not available');
    }

    public function getSyncUrl()
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/tracking/syncOrder', array(
            'order_id' => $this->_getOrder()->getId()
        ));
    }

    protected function _toHtml()
    {
        if ($this->_helper->isEnabled() && $this->isTrackingSupported()) {
            return parent::_toHtml();
        }

        return '';
    }
}