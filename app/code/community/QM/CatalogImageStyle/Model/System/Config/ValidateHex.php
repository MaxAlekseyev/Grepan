<?php

class QM_CatalogImageStyle_Model_System_Config_ValidateHex extends Mage_Core_Model_Config_Data
{
    protected function _beforeSave()
    {
        $value = $this->getValue();

        if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',$value))
        {
            Mage::throwException(Mage::helper('qm_catalogimagestyle')->__('Wrong hex format:') . $value);
        }

        return $this;
    }
}