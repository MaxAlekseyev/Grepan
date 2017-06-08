<?php

class QM_Gtm_Block_Gtmcode extends Mage_Core_Block_Template
{
    protected $_helper;

    protected function _construct()
    {
        $this->_helper = Mage::helper ('qm_gtm');
    }

    protected function _getGTMId()
    {
        return $this->_helper->getGtmId();
    }

    protected function _toHtml()
    {
        if (!$this->_helper->isEnabled()) {
            return '';
        }

        return parent::_toHtml();
    }
}
