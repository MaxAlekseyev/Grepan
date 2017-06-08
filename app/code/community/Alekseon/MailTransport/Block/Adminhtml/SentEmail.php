<?php
/**
 * @author    Marcin Frymark
 * @email     contact@alekseon.com
 * @company   Alekseon
 * @website   www.alekseon.com
 */
 class Alekseon_MailTransport_Block_Adminhtml_SentEmail extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'alekseon_mailTransport';
        $this->_controller = 'adminhtml_sentEmail';
        $this->_headerText = Mage::helper('alekseon_mailTransport')->__('Sent Emails History');
        parent::__construct();
        $this->removeButton('add');
    }

}