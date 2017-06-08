<?php

class QM_Switcher_Model_Adminhtml_System_Attribute extends Mage_Core_Model_Config_Data
{
    /**
     * serialize values before save
     * @return Mage_Core_Model_Abstract
     */
    public function _beforeSave()
    {
        $value = $this->getCoreHelper()->jsonEncode($this->getValue());
        $this->setValue($value);
        return parent::_beforeSave();
    }

    /**
     * unserialize values after load
     * @return Mage_Core_Model_Abstract|void
     */
    public function _afterLoad()
    {
        parent::_afterLoad();
        $value = $this->getCoreHelper()->jsonDecode($this->getValue());
        $this->setValue($value);
    }

    /**
     * @return Mage_Core_Helper_Data
     */
    public function getCoreHelper()
    {
        return Mage::helper('core');
    }
}
