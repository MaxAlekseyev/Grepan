<?php

class QM_NewFromTo_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected function _getAfterDays()
    {
        return Mage::getStoreConfig(
            'qmnewfromto/config/new_after'
        );
    }

    protected function _getDaysCount()
    {
        return Mage::getStoreConfig(
            'qmnewfromto/config/count_new_days'
        );
    }

    public function isEnable()
    {
        return Mage::getStoreConfig(
            'qmnewfromto/settings/enabled'
        );
    }

    public function getFromDate()
    {
        return $this->_getCurrentDateWithDayIncrement($this->_getAfterDays());
    }

    public function getToDate()
    {
        return $this->_getCurrentDateWithDayIncrement($this->_getAfterDays() + $this->_getDaysCount());
    }

    protected function _getCurrentDateWithDayIncrement($days)
    {
        $date = new DateTime();
        $date->setTimestamp(Mage::getModel('core/date')->timestamp());
        $date->add(new DateInterval('P'.(int)$days.'D'));

        return $date->getTimestamp();
    }
}