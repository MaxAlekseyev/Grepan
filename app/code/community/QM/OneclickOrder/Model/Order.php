<?php

class QM_OneclickOrder_Model_Order extends Mage_Core_Model_Abstract
{
    const ENTITY    = 'qmoneclickorder_order';
    const CACHE_TAG = 'qmoneclickorder_order';

    protected $_eventPrefix = 'qmoneclickorder_order';
    protected $_eventObject = 'order';

    public function _construct()
    {
        parent::_construct();
        $this->_init('qmoneclickorder/order');
    }

    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()){
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 'new';
        return $values;
    }
}
