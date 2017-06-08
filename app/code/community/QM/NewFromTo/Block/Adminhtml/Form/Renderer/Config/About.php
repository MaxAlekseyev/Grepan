<?php

class QM_NewFromTo_Block_Adminhtml_Form_Renderer_Config_About
    extends Mage_Adminhtml_Block_Abstract
        implements Varien_Data_Form_Element_Renderer_Interface
{

    const TEMPLATE_NAME = 'QM_NewFromTo';

    protected function _getTemplate($locale = 'en_US')
    {
        return implode(array(
            Mage::getBaseDir('locale'),
            $locale,
            'template',
            'adminhtml',
            self::TEMPLATE_NAME . '.html'
        ), DS);
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $locale = Mage::app()->getLocale()->getLocaleCode();
        $template = $this->_getTemplate($locale);

        if (!file_exists($template)) {
            $template = $this->_getTemplate();
        }

        if (file_exists($template)) {
            return file_get_contents($template);
        }

        return self::TEMPLATE_NAME;
    }
}
