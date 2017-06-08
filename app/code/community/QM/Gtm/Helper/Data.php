<?php

class QM_Gtm_Helper_Data extends Mage_Core_Helper_Data
{
    public function isEnabled()
    {
        return Mage::getStoreConfigFlag('qm_gtm/settings/enabled');
    }

    public function getGtmId()
    {
        return Mage::getStoreConfig('qm_gtm/settings/gtm_id');
    }
}
