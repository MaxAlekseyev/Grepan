<?php

class QM_OneclickOrder_Block_Order extends Mage_Core_Block_Template
{
    const ROUTE_CALLBACK_ADD = 'oneclickorder/order/add';

    public function getPostActionUrl()
    {
        return $this->helper('qmoneclickorder')->getPostActionUrl(self::ROUTE_CALLBACK_ADD);
    }

    protected function _isValidCustomerGroup()
    {
        $availableGroupsSetting = Mage::getStoreConfig('qmoneclickorder/general/customer_groups');
        if (!strlen($availableGroupsSetting)) {
            return false;
        }
        $availableGroups = explode(',', $availableGroupsSetting);
        $customerGroup   = Mage::getSingleton('customer/session')->getCustomerGroupId();

        return in_array($customerGroup, $availableGroups);
    }

    protected function _isValidProduct()
    {
        $product = Mage::registry('current_product');
        return $product->getEnableOneClickOrder();
    }

    public function canShowBlock()
    {
        return Mage::getStoreConfig('qmoneclickorder/general/enabled')
            && $this->_isValidCustomerGroup()
            && $this->_isValidProduct();
    }

    public function canShowName()
    {
        return Mage::getStoreConfig('qmoneclickorder/general/show_name');
    }

    public function canShowEmail()
    {
        return Mage::getStoreConfig('qmoneclickorder/general/show_email');
    }

    public function canShowTelephone()
    {
        return Mage::getStoreConfig('qmoneclickorder/general/show_telephone');
    }

    public function isAjaxEnabled()
    {
        return (int)Mage::getStoreConfig('qmoneclickorder/general/enable_ajax');
    }

    public function getInsertButtonSelector()
    {
        return Mage::getStoreConfig('qmoneclickorder/general/insert_selector');
    }

    public function canShowComment()
    {
        return Mage::getStoreConfig('qmoneclickorder/general/show_comment');
    }

    public function getCurrentProductId()
    {
        $product = Mage::registry('current_product');
        return $product->getId() ? $product->getId() : 0;
    }
}