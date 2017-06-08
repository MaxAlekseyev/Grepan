<?php
/**
 * @author    Marcin Frymark
 * @email     contact@alekseon.com
 * @company   Alekseon
 * @website   www.alekseon.com
 */
class Alekseon_MailTransport_Model_Observer
{
    public function removeOldSentEmails()
    {
        $sentEmails = Mage::getModel('alekseon_mailTransport/sentEmail')->getCollection();
        foreach($sentEmails as $sentEmail) {
            $sentAt = $sentEmail->getSentAt();
            $now = Mage::getModel('core/date')->gmtDate();
            $sentAtTime = strtotime($sentAt);
            $nowTime = strtotime($now);
            $daysDifference = floor(($nowTime - $sentAtTime) / (60 * 60 * 24));
            
            if ($sentEmail->getStatus()) {
                $deleteAfter = Mage::getStoreConfig('alekseon_mailTransport/sent_email_history/remove_successed_after');
            } else {
                $deleteAfter = Mage::getStoreConfig('alekseon_mailTransport/sent_email_history/remove_failed_after');
            }

            if (is_numeric($deleteAfter) && $deleteAfter <= $daysDifference) {
                $sentEmail->delete();
            }
        }
    }
}