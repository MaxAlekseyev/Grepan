<?php
/**
 * @author    Marcin Frymark
 * @email     contact@alekseon.com
 * @company   Alekseon
 * @website   www.alekseon.com
 */
class Alekseon_MailTransport_Block_Adminhtml_SentEmail_TopMessage extends Mage_Adminhtml_Block_Template
{
    public function canDisplay()
    {
        if (Mage::helper('alekseon_mailTransport')->isSentEmailHistoryEnabled()) {
            return false;
        }
        
        return true;
    }
}
