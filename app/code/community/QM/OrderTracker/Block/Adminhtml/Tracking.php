<?php

class QM_OrderTracker_Block_Adminhtml_Tracking
    extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected $_tracking;
    protected $_helper;

    protected function _getOrder()
    {
        return Mage::registry('current_order');
    }

    protected function _construct()
    {
        $this->_tracking = Mage::getModel('qm_ordertracker/tracking')->load($this->_getOrder()->getId(), 'order_id');
        $this->_helper = Mage::helper('qm_ordertracker');
    }

    public function getTabLabel()
    {
        return $this->__('Tracking');
    }

    public function getTabTitle()
    {
        return $this->__('Order Tracking');
    }

    public function canShowTab()
    {
        return $this->_helper->isEnabled();
    }

    public function isHidden()
    {
        return false;
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
}