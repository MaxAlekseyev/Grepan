<?php
class QM_Core_Block_Adminhtml_Form_Renderer_Config_CreateStoreView
    extends Mage_Adminhtml_Block_Abstract
        implements Varien_Data_Form_Element_Renderer_Interface
{

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = <<<DATA
<p>Fix preg_replace bug on store view creating</p>
DATA;
        return $html;
    }
}
