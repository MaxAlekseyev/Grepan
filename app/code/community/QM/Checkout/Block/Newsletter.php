<?php

class QM_Checkout_Block_Newsletter extends Mage_Core_Block_Template
{
    /**
     * Convert block to html sting.
     * Checks is possible to show newsletter checkbox
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->helper('qmcheckout')->isVisibleNewsletter()
            || Mage::helper('qmcheckout')->isCustomerSubscribed()
        ) {
            return '';
        }

        return parent::_toHtml();
    }
}
