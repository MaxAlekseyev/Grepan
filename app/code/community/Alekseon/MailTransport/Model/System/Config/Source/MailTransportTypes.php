<?php
/**
 * @author    Marcin Frymark
 * @email     contact@alekseon.com
 * @company   Alekseon
 * @website   www.alekseon.com
 */
class Alekseon_MailTransport_Model_System_Config_Source_MailTransportTypes
{
    const MAIL_TARNSPORT_TYPE_DEFAULT = 0;
    const MAIL_TARNSPORT_TYPE_SMTP = 1;
    const MAIL_TARNSPORT_TYPE_FILE = 2;
    
    public function toOptionArray($short = false)
    {
        $helper = Mage::helper('alekseon_mailTransport');
        if ($short) {
            $options = array(
                self::MAIL_TARNSPORT_TYPE_DEFAULT => $helper->__('PHP'),
                self::MAIL_TARNSPORT_TYPE_SMTP    => $helper->__('SMTP'),
                self::MAIL_TARNSPORT_TYPE_FILE    => $helper->__('File'),
            );
        } else {
            $options = array(
                self::MAIL_TARNSPORT_TYPE_DEFAULT => $helper->__('PHP - PHP internal mail()'),
                self::MAIL_TARNSPORT_TYPE_SMTP    => $helper->__('SMTP - Simple Mail Transfer Protocol'),
                self::MAIL_TARNSPORT_TYPE_FILE    => $helper->__('File - Saves e-mail message to a file.'),
            );        
        }
        return $options;
    }

}