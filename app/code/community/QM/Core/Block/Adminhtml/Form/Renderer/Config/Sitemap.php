<?php
class QM_Core_Block_Adminhtml_Form_Renderer_Config_Sitemap
    extends Mage_Adminhtml_Block_Abstract
        implements Varien_Data_Form_Element_Renderer_Interface
{

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = <<<DATA
<p>Now you can create sitemap file with any name</p>
DATA;
        return $html;
    }
}
