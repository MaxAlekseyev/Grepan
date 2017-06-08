<?php

class QM_NewFromTo_Model_System_Config_IntValidator extends Mage_Core_Model_Config_Data
{
    protected function _beforeSave()
    {
        $value = $this->getValue();
        if ($value && (!filter_var($value, FILTER_VALIDATE_INT) || $value < 0)) {
            Mage::throwException(Mage::helper('qmnewfromto')->__('Invalid days count:') . $value);
        }
        return $this;
    }
}