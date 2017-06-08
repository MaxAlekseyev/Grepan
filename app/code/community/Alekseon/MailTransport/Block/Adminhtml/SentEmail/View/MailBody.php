<?php
/**
 * @author    Marcin Frymark
 * @email     contact@alekseon.com
 * @company   Alekseon
 * @website   www.alekseon.com
 */
class Alekseon_MailTransport_Block_Adminhtml_SentEmail_View_MailBody extends Mage_Adminhtml_Block_Abstract
{
    public function getEmail()
    {
        return Mage::registry('current_sentEmail');
    }

    protected function _toHtml()
    {
        $mailBody = $this->getEmail()->getMailBody();
        if ($this->getEmail()->getTemplateType() == Mage_Core_Model_Template::TYPE_TEXT) {
            $mailBody = nl2br($mailBody);
        }
        return $mailBody;
    }
}