<?php

class QM_OrderTracker_Model_Tracking extends Mage_Core_Model_Abstract
{
    const ENTITY    = 'qm_ordertracker_tracking';
    const CACHE_TAG = 'qm_ordertracker_tracking';

    protected $_eventPrefix = 'qm_ordertracker_tracking';
    protected $_eventObject = 'tracking';

    public function _construct()
    {
        parent::_construct();
        $this->_init('qm_ordertracker/tracking');
    }

    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        $this->setUpdatedAt($now);
        return $this;
    }
}