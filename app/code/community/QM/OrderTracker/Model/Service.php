<?php

class QM_OrderTracker_Model_Service
{
    function updateOrder(Mage_Sales_Model_Order $order)
    {
        $tracking = Mage::getModel('qm_ordertracker/tracking')
            ->load($order->getId(), 'order_id')
            ->setOrderId($order->getId());

        Mage::dispatchEvent('qm_ordertracker_data_collect',
            array('tracking' => $tracking, 'order' => $order));

        $tracking->save();
    }

    function updateAllOrders()
    {
        $orders = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('status', array('nin' => array('canceled','complete')));

        foreach ($orders as $order) {
            $this->updateOrder($order);
        }
    }
}