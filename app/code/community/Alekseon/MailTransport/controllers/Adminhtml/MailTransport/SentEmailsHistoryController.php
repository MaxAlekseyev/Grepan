<?php
/**
 * @author    Marcin Frymark
 * @email     contact@alekseon.com
 * @company   Alekseon
 * @website   www.alekseon.com
 */
class Alekseon_MailTransport_Adminhtml_MailTransport_SentEmailsHistoryController extends Mage_Adminhtml_Controller_Action
{
    public function _initSentEmail()
    {
        $sentEmailId = (int) $this->getRequest()->getParam('id');
        $sentEmail = Mage::getModel('alekseon_mailTransport/sentEmail');

        if ($sentEmailId) {
            $sentEmail->load($sentEmailId);
        }

        Mage::register('current_sentEmail', $sentEmail);
        return $this;    
    }

    public function indexAction()
    {
        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }
    
        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody($this->getLayout()->createBlock('alekseon_mailTransport/adminhtml_sentEmail_grid')->toHtml());
    }
    
    public function viewAction()
    {
        $this->_initSentEmail();
        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function deleteAction()
    {
        $this->_initSentEmail();
        $sentEmail = Mage::registry('current_sentEmail');    
        
        try {
            $sentEmail->delete();
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->getResponse()->setRedirect($this->getUrl('*/*/view', array('id' => $sentEmail->getId())));
            return;
        }
        
        $this->_getSession()->addSuccess(
            Mage::helper('alekseon_mailTransport')->__('Email has been removed.')
        );
        
        $this->getResponse()->setRedirect($this->getUrl('*/*/index'));
    }
    
    public function mailBodyAction()
    {
        $this->_initSentEmail();
        $this->loadLayout();
        $this->renderLayout();
    }
}