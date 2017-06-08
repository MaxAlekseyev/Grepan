<?php

class QM_OrderTracker_Model_Resource_Tracking_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('qmoneclickorder/order');
    }


}
