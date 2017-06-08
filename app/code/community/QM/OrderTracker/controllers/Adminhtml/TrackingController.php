<?php

class QM_OrderTracker_Adminhtml_TrackingController extends Mage_Adminhtml_Controller_Action
{
    public function syncOrderAction()
    {
        try {
            $orderId = $this->getRequest()->getParam('order_id');

            $order = Mage::getModel('sales/order')->load($orderId);

            Mage::getSingleton('qm_ordertracker/service')->updateOrder($order);
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirectReferer();


    }

    public function syncAllOrdersAction()
    {
        try {
            Mage::getSingleton('qm_ordertracker/service')->updateAllOrders();
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirectReferer();
    }
}
