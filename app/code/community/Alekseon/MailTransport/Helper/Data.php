<?php
/**
 * @author    Marcin Frymark
 * @email     contact@alekseon.com
 * @company   Alekseon
 * @website   www.alekseon.com
 */
class Alekseon_MailTransport_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getAlekseonUrl()
    {   
        return 'http://www.alekseon.com';
    }
    
    public function isSentEmailHistoryEnabled()
    {
        return Mage::getStoreConfig('alekseon_mailTransport/sent_email_history/enabled');
    }
}