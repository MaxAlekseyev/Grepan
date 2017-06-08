<?php
/**
 * @author    Marcin Frymark
 * @email     contact@alekseon.com
 * @company   Alekseon
 * @website   www.alekseon.com
 */
class Alekseon_MailTransport_Model_System_Config_Source_SmtpAuthModes
{
    const SMTP_AUTH_MODE_LOGIN   = 'login';
    const SMTP_AUTH_MODE_PLAIN   = 'plain';
    const SMTP_AUTH_MODE_CRAMMD5 = 'cramm5';
    
    public function toOptionArray()
    {
        $helper = Mage::helper('alekseon_mailTransport');
        $options = array(
            self::SMTP_AUTH_MODE_LOGIN   => $helper->__('Login'),
            self::SMTP_AUTH_MODE_PLAIN   => $helper->__('Plain'),
            self::SMTP_AUTH_MODE_CRAMMD5 => $helper->__('Cram-md5'),
        );
        return $options;
    }

}