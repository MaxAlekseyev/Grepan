<?php
/**
 * @author    Marcin Frymark
 * @email     contact@alekseon.com
 * @company   Alekseon
 * @website   www.alekseon.com
 */
class Alekseon_MailTransport_Model_System_Config_Source_EncryptionProtocols
{
    const ENCRYPTION_PROTOCOL_SSL = 'ssl';
    const ENCRYPTION_PROTOCOL_TLS = 'tls';

    public function toOptionArray()
    {
        $helper = Mage::helper('alekseon_mailTransport');
        $options = array(
            0 => $helper->__('No Encryption'),
            self::ENCRYPTION_PROTOCOL_SSL => $helper->__('SSL - Secure Sockets Layer'),
            self::ENCRYPTION_PROTOCOL_TLS => $helper->__('TLS - Transport Layer Security'),
        );
        return $options;
    }

}