<?php

class QM_OneclickOrder_Helper_Order extends Mage_Core_Helper_Abstract
{
    public function canShowConfirmation()
    {
        return Mage::getStoreConfig('qmoneclickorder/general/enable_confirmation');
    }

    public function getConfirmationText()
    {
        return Mage::getStoreConfig('qmoneclickorder/general/confirmation_text');
    }

    public function getConfirmationHtml()
    {
        $layout = Mage::app()->getLayout();
        $confirmation = $layout->createBlock('core/template')
            ->setTemplate('qm/oneclickorder/order/confirmation.phtml')
            ->setConfirmationText($this->getConfirmationText())
        ;
        return $confirmation->toHtml();
    }

    public function isEmailNotificationEnabled()
    {
        return Mage::getStoreConfig('qmoneclickorder/notifications/email_notification_enabled');
    }

    public function getEmailNotificationAddressList()
    {
        $addresses = Mage::getStoreConfig('qmoneclickorder/notifications/send_email_notification_to');
        $addresses = explode("\n", $addresses);
        return $addresses;
    }

    public function getEmailNotificationTemplate()
    {
        return Mage::getStoreConfig('qmoneclickorder/notifications/email_template');
    }
}