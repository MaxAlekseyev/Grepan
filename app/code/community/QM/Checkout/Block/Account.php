<?php

class QM_Checkout_Block_Account extends Mage_Core_Block_Template
{
    protected function _toHtml()
    {
        $helper = Mage::helper('qmcheckout');
        if ($helper->isCustomerLoggedIn() || !$helper->isVisibleCreateAccount()) {
            return '';
        }
        return parent::_toHtml();
    }
}
