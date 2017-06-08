<?php

class QM_AdvancedFeedback_Model_Callback extends Mage_Core_Model_Abstract
{
    const ENTITY    = 'qmadvfeedback_callback';
    const CACHE_TAG = 'qmadvfeedback_callback';

    protected $_eventPrefix = 'qmadvfeedback_callback';
    protected $_eventObject = 'callback';

    public function _construct()
    {
        parent::_construct();
        $this->_init('qmadvfeedback/callback');
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
