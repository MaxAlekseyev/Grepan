<?php

class QM_OrderTracker_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function isEnabled()
    {
        return Mage::getStoreConfig('qm_ordertracker/settings/enabled');
    }
}