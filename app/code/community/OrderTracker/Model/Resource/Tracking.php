<?php

class QM_OrderTracker_Model_Resource_Tracking extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('qm_ordertracker/tracking', 'entity_id');
    }


}
