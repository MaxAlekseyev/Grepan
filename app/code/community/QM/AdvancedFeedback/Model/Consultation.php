<?php

class QM_AdvancedFeedback_Model_Consultation extends Mage_Core_Model_Abstract
{
    const ENTITY    = 'qmadvfeedback_consultation';
    const CACHE_TAG = 'qmadvfeedback_consultation';

    protected $_eventPrefix = 'qmadvfeedback_consultation';
    protected $_eventObject = 'consultation';

    public function _construct()
    {
        parent::_construct();
        $this->_init('qmadvfeedback/consultation');
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
