<?php
class QM_Core_Block_Adminhtml_Form_Renderer_Config_AddCss
    extends Mage_Adminhtml_Block_Abstract
        implements Varien_Data_Form_Element_Renderer_Interface
{

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = <<<DATA
<p>Add Css Files</p>
DATA;
        return $html;
    }
}
