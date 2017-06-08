<?php
/**
 * @author    Marcin Frymark
 * @email     contact@alekseon.com
 * @company   Alekseon
 * @website   www.alekseon.com
 */
class Alekseon_MailTransport_Model_System_Config_Source_AllTemplates
{
    protected $_options;
    
    public function toOptionArray()
    {
        if (is_null($this->_options)) {
            $emailTemplateModel = Mage::getModel('core/email_template');
            $this->options = array();
            
            $idLabel = array();
            foreach ($emailTemplateModel->getDefaultTemplates() as $templateId => $row) {
                if (isset($row['@']) && isset($row['@']['module'])) {
                    $module = $row['@']['module'];
                } else {
                    $module = 'adminhtml';
                }
                $idLabel[$templateId] = Mage::helper($module)->__($row['label']);
            }
            
            foreach ($emailTemplateModel->getCollection() as $template) {
                $idLabel[$template->getId()] = $template->getTemplateCode();
            }
            
            asort($idLabel);
            foreach ($idLabel as $templateId => $label) {
                $this->_options[] = array('value' => $templateId, 'label' => $label);
            }
        }
        
        return $this->_options;
    }
}