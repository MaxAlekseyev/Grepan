<?php
/**
 * @author    Marcin Frymark
 * @email     contact@alekseon.com
 * @company   Alekseon
 * @website   www.alekseon.com
 */
class Alekseon_MailTransport_Model_Email_Template extends Mage_Core_Model_Email_Template
{
    protected $_sentEmailModel;

    protected function _isTemplateConfidential()
    {
        $confidentialTemplatesConfig = Mage::getStoreConfig('alekseon_mailTransport/sent_email_history/confidential_templates');
        
        $confidentialTemplates = explode(',', $confidentialTemplatesConfig);
        
        if (in_array($this->getTemplateId(), $confidentialTemplates)) {
            return true;
        }

        return false;
    }
    
    protected function _getReturnPath()
    {
        $setReturnPath = Mage::getStoreConfig(Mage_Core_Model_Email_Template::XML_PATH_SENDING_SET_RETURN_PATH);
        switch ($setReturnPath) {
            case 1:
                $returnPathEmail = $this->getSenderEmail();
                break;
            case 2:
                $returnPathEmail = Mage::getStoreConfig(Mage_Core_Model_Email_Template::XML_PATH_SENDING_RETURN_PATH_EMAIL);
                break;
            default:
                $returnPathEmail = null;
                break;
        }    

        return $returnPathEmail;
    }
    
    public function getProcessedTemplate(array $variables = array())
    {
        $processedResult = parent::getProcessedTemplate($variables);

        if ($this->_isTemplateConfidential()) {
            $this->getSentEmailModel()->setIsConfidential(true);
        } else {
            $this->getSentEmailModel()->setMailBody($processedResult);
        }

        return $processedResult;
    }

    public function getProcessedTemplateSubject(array $variables)
    {
        $processedResult = parent::getProcessedTemplateSubject($variables);
        $this->getSentEmailModel()->setSubject($processedResult);
        return $processedResult;
    }
    
    public function getMailTransportConfig($configField, $storeId)
    {
        return Mage::getStoreConfig('alekseon_mailTransport/general/' . $configField, $storeId);
    }

    public function getMail($storeId = null)
    {
        if (is_null($this->_mail)) {            
            $this->_mail = parent::getMail();
            $transport = $this->_getTransport($storeId);
            if ($transport) {
                $this->_mail->setDefaultTransport($transport);
            }
        }
        return $this->_mail;
    }
    
    protected function _getTransport($storeId = null)
    {
        $transport = null;
        $transportType = $this->getMailTransportConfig('type', $storeId);
        
        if (!$transportType) {
            $transportType = Alekseon_MailTransport_Model_System_Config_Source_MailTransportTypes::MAIL_TARNSPORT_TYPE_DEFAULT;
        }
        
        switch($transportType) {
            case Alekseon_MailTransport_Model_System_Config_Source_MailTransportTypes::MAIL_TARNSPORT_TYPE_SMTP:
                $transport = $this->_getSmtpTransport($storeId);
                break;
            case Alekseon_MailTransport_Model_System_Config_Source_MailTransportTypes::MAIL_TARNSPORT_TYPE_FILE:
                $transport = $this->_getFileTransport($storeId);
                break;
            default:
                $transport = $this->_getPhpTransport();
        }

        return $transport;
    }
    
    protected function _getPhpTransport()
    {
        $returnPathEmail = $this->_getReturnPath();
        if ($returnPathEmail) {
            $trasnport = new Zend_Mail_Transport_Sendmail("-f".$returnPathEmail);
        } else {
            $trasnport = new Zend_Mail_Transport_Sendmail();
        }
        return $trasnport;
    }
    
    protected function _getSmtpTransport($storeId = null)
    {
        $host = $this->getMailTransportConfig('smtp_host', $storeId);
        $config = array(
            'username' => $this->getMailTransportConfig('smtp_user', $storeId),
            'password' => $this->getMailTransportConfig('smtp_password', $storeId),
            'port'     => $this->getMailTransportConfig('smtp_port', $storeId),
            'auth'     => $this->getMailTransportConfig('smtp_auth', $storeId),
        );

        $ssl = $this->getMailTransportConfig('smtp_ssl', $storeId);
        if ($ssl) {
            $config['ssl'] = $ssl;
        }

        $transport = new Zend_Mail_Transport_Smtp($host, $config);
        
        $returnPathEmail = $this->_getReturnPath();

        if ($returnPathEmail) {
            $this->_mail->setReturnPath($returnPathEmail);
        }
        
        return $transport;
    }
    
    protected function _getFileTransport($storeId = null)
    {
        $path = $this->getMailTransportConfig('file_path', $storeId);
        if ($path) {
            $path = implode(DS, explode('/', $path));
            $path = implode(DS, explode('\\', $path));
            $path = Mage::getBaseDir() . DS . $path;
        }
    
        $config = array(
            'path' => $path,
            //'callback' =>
        );
        $transport = new Zend_Mail_Transport_File($config);
        return $transport;
    }
    
    protected function _setTransportType()
    {
        $defaultTransportType = Zend_Mail::getDefaultTransport();
        if ($defaultTransportType instanceof Zend_Mail_Transport_File) {
            $transportType = Alekseon_MailTransport_Model_System_Config_Source_MailTransportTypes::MAIL_TARNSPORT_TYPE_FILE;
        } else if ($defaultTransportType instanceof Zend_Mail_Transport_Smtp) {
            $transportType = Alekseon_MailTransport_Model_System_Config_Source_MailTransportTypes::MAIL_TARNSPORT_TYPE_SMTP;
        } else {
            $transportType = Alekseon_MailTransport_Model_System_Config_Source_MailTransportTypes::MAIL_TARNSPORT_TYPE_DEFAULT;
        }

        $this->getSentEmailModel()->setTransportType($transportType);
        return $this;
    }
    
    public function send($email, $name = null, array $variables = array())
    {
        $this->getSentEmailModel()->addSender($this->getSenderEmail(), $this->getSenderName());
        $this->getSentEmailModel()->setTemplateId($this->getId());
    
        if (isset($variables['store'])) {
            $store = $variables['store'];
        } else {
            $store = Mage::app()->getStore();
        }
        
        $this->getMail($store->getId());
        $this->getSentEmailModel()->setStoreId($store->getId()); 

        /* set return path to false, to avoid changing transport type by send method in parent class */
        /* we check this configuration in _getReturnPath method, and we set return path before */
        Mage::app()->getStore()->setConfig(Mage_Core_Model_Email_Template::XML_PATH_SENDING_SET_RETURN_PATH, false);
        
        try {        
            $result = parent::send($email, $name, $variables);            
            $this->_saveSentEmail($email, $name, $result);
        } catch (Exception $e) {
            $this->getSentEmailModel()->setComment($e->getMessage());
            $this->_saveSentEmail($email, $name, false);
            Mage::throwException($e->getMessage());
        }            

        return $result;
    }
    
    public function getSentEmailModel()
    {
        if (is_null($this->_sentEmailModel)) {
            $this->_sentEmailModel = Mage::getModel('alekseon_mailTransport/sentEmail');
        }
        return $this->_sentEmailModel;
    }
    
    protected function _saveSentEmail($email, $name, $result)
    {
        if (!Mage::helper('alekseon_mailTransport')->isSentEmailHistoryEnabled()) {
            return;
        }
    
        $emails = array_values((array)$email);
        $names = is_array($name) ? $name : (array)$name;
        $names = array_values($names);

        try {
            foreach ($emails as $key => $email) {
                $name = isset($names[$key]) ? $names[$key] : null;
                $this->getSentEmailModel()->addReceipient($email, $name);
            }
            $this->_setTransportType();
            $this->getSentEmailModel()->setTemplateType($this->getTemplateType());
            $this->getSentEmailModel()->setSentAt(Mage::getSingleton('core/date')->gmtDate());
            $this->getSentEmailModel()->setStatus($result);            
            $this->getSentEmailModel()->save();
            $this->getSentEmailModel()->unsetData();
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }
    
    public function addBcc($bcc)
    {
        if (is_array($bcc)) {
            foreach ($bcc as $email) {
                $this->getSentEmailModel()->addBccReceipient($email);
            }
        } elseif ($bcc) {
           $this->getSentEmailModel()->addBccReceipient($bcc);
        }
        return parent::addBcc($bcc);
    }
}