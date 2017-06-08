<?php
/**
 * @author    Marcin Frymark
 * @email     contact@alekseon.com
 * @company   Alekseon
 * @website   www.alekseon.com
 */
class Alekseon_MailTransport_Block_Adminhtml_SentEmail_View_Form extends Mage_Adminhtml_Block_Template
{
    protected $_correspondents = array();

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('alekseon/mailTransport/sentEmail/view/form.phtml');
        $this->_prepareCorrespondents();
    }
    
    protected function _prepareCorrespondents()
    {
        foreach($this->getEmail()->getCorrespondents() as $correspondent) {
            $this->_correspondents[$correspondent->getType()][] = $correspondent;
        }
        return $this;
    }
    
    public function getEmail()
    {
        return Mage::registry('current_sentEmail');
    }
    
    public function getSubject()
    {
        return $this->getEmail()->getSubject();
    }
    
    public function getMailBodyIframeUrl()
    {
        return $this->getUrl('*/*/mailBody', array('id' => $this->getEmail()->getId()));
    }
    
    public function getSentAt()
    {
        return $this->formatDate($this->getEmail()->getSentAt(), 'medium', true);
    }
    
    public function getStatus()
    {
        return $this->getEmail()->getStatus();
    }
    
    public function getComment()
    {
        return $this->getEmail()->getComment();
    }
    
    public function getSentFrom()
    {
        $storeId = $this->getEmail()->getStoreId();
        $store = Mage::app()->getStore($storeId);
        $name = array(
            $store->getWebsite()->getName(),
            $store->getGroup()->getName(),
            $store->getName()
        );
        return implode('<br/>', $name);
    }
    
    public function getTransportType()
    {
        $transportType = $this->getEmail()->getTransportType();
        $trasnportTypes = Mage::getSingleton('alekseon_mailTransport/system_config_source_mailTransportTypes')->toOptionArray();
        if (isset($trasnportTypes[$transportType])) {
            return $trasnportTypes[$transportType];
        } else {
            return Mage::helper('alekseon_mailTransport')->__('Unknown');
        }
    }
    
    public function getUsedTemplate()
    {
        $templateId = $this->getEmail()->getTemplateId();
        $mailTemplate = Mage::getModel('core/email_template');
        
        if (is_numeric($templateId)) {
            $mailTemplate->load($templateId);
            if ($mailTemplate->getId()) {
                return Mage::helper('alekseon_mailTransport')->__('Transactional Email Template') . ':<br/>' . $mailTemplate->getTemplateCode();
            }
        } else {
            $defaultTemplates = $mailTemplate->getDefaultTemplates();
            if (isset($defaultTemplates[$templateId])) {
                $data = &$defaultTemplates[$templateId];
                return Mage::helper('alekseon_mailTransport')->__('Default Template') . ':<br/>' . $templateId . '<br/>' . Mage::helper('alekseon_mailTransport')->__('File') . ': ' . $data['file'];
            }
        }
        
        return $templateId;
    }
    
    public function getSender()
    {
        if (isset($this->_correspondents[Alekseon_MailTransport_Model_SentEmail_Correspondent::TYPE_SENDER])) {
            return $this->_correspondents[Alekseon_MailTransport_Model_SentEmail_Correspondent::TYPE_SENDER];
        }
        return array();
    }
    
    public function getReceipients()
    {
        if (isset($this->_correspondents[Alekseon_MailTransport_Model_SentEmail_Correspondent::TYPE_RECEIPIENT])) {
            return $this->_correspondents[Alekseon_MailTransport_Model_SentEmail_Correspondent::TYPE_RECEIPIENT];
        }
        return array();
    }
    
    public function getBccReceipients()
    {
        if (isset($this->_correspondents[Alekseon_MailTransport_Model_SentEmail_Correspondent::TYPE_BCC])) {
            return $this->_correspondents[Alekseon_MailTransport_Model_SentEmail_Correspondent::TYPE_BCC];
        }
        return array();
    }
    
    public function getTemplateType()
    {
        $templateType = $this->getEmail()->getTemplateType();
        switch($templateType) {
            case Mage_Core_Model_Template::TYPE_TEXT:
                return Mage::helper('alekseon_mailTransport')->__('Text');
            case Mage_Core_Model_Template::TYPE_HTML:
                return Mage::helper('alekseon_mailTransport')->__('Html');
            default:
                return false;
        }
    }
    
    public function isContentConfidential()
    {
        return $this->getEmail()->getIsConfidential();
    }
    
    public function getController()
    {
        return $this->getEmail()->getController();
    }
}
