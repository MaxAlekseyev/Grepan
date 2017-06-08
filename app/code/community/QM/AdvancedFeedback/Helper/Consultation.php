<?php

class QM_AdvancedFeedback_Helper_Consultation extends Mage_Core_Helper_Abstract
{
    const SENDER_EMAIL_IDENTITY = 'qmadvfeedback/consultation/sender_email_identity';

    public function canShowConfirmation()
    {
        return Mage::getStoreConfig('qmadvfeedback/consultation/enable_confirmation');
    }

    public function getConfirmationText()
    {
        return Mage::getStoreConfig('qmadvfeedback/consultation/confirmation_text');
    }

    public function getConfirmationHtml()
    {
        $layout = Mage::app()->getLayout();
        $confirmation = $layout->createBlock('core/template')
            ->setTemplate('qm/advfeedback/consultation/confirmation.phtml')
            ->setConfirmationText($this->getConfirmationText())
        ;
        return $confirmation->toHtml();
    }

    public function isEmailNotificationEnabled()
    {
        return Mage::getStoreConfig('qmadvfeedback/consultation/email_notification_enabled');
    }

    public function getEmailNotificationAddressList()
    {
        $addresses = Mage::getStoreConfig('qmadvfeedback/consultation/send_email_notification_to');
        $addresses = explode("\n", $addresses);
        return $addresses;
    }

    public function sendNotifications($data)
    {
        if ($this->isEmailNotificationEnabled()) {
            $addresses = $this->getEmailNotificationAddressList();

            $emailTemplate = Mage::getModel('core/email_template')->loadDefault('consultation_email_template');
            $emailTemplate->setSenderEmail($this->_getSenderEmail());
            $emailTemplate->setSenderName($this->_getSenderName());
            $emailTemplate->setTemplateSubject('Subject: Consultation');

            $data['user_name'] = isset($data['name']) ? $data['name'] : '';//fix name in template use like sender name
            $data['user_email'] = isset($data['email']) ? $data['email'] : '';//fix email in template use like sender email

            if (count($addresses)) {
                foreach ($addresses as $address) {
                    $address = trim($address);
                    if (!Zend_Validate::is($address, 'EmailAddress')) {
                        Mage::log($this->__('Invalid email address "%s".', $address));
                        continue;
                    }

                    if (!$emailTemplate->send($address, $this->_getSenderName(), $data)) {
                        Mage::log($this->__('There was a problem sending while email senidg.'));
                    }
                }
            } else {
                Mage::log($this->__('There was a problem sending email notification.'));
            }
        }
    }

    protected function _getSenderEmail()
    {
        $node = array(
            'trans_email',
            'ident_' . Mage::getStoreConfig(self::SENDER_EMAIL_IDENTITY),
            'email'
        );
        return Mage::getStoreConfig(implode($node, '/'));
    }

    protected function _getSenderName()
    {
        $node = array(
            'trans_email',
            'ident_' . Mage::getStoreConfig(self::SENDER_EMAIL_IDENTITY),
            'name'
        );
        return Mage::getStoreConfig(implode($node, '/'));
    }
}