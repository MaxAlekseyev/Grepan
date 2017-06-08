<?php
/**
 * @author    Marcin Frymark
 * @email     contact@alekseon.com
 * @company   Alekseon
 * @website   www.alekseon.com
 */
class Alekseon_MailTransport_Block_Adminhtml_SentEmail_View extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {            
        $this->_mode = 'view';
        $this->_blockGroup = 'alekseon_mailTransport';
        $this->_controller = 'adminhtml_sentEmail';

        parent::__construct();

        $this->_removeButton('save');
        $this->_removeButton('reset');
    }
    
    public function getHeaderText()
    {
        return Mage::helper('alekseon_mailTransport')->__('Sent Email');
    }
}
