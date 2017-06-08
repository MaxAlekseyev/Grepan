<?php
class QM_Core_Block_Adminhtml_Form_Renderer_Config_DisableBlocks
    extends Mage_Adminhtml_Block_Abstract
        implements Varien_Data_Form_Element_Renderer_Interface
{

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = <<<DATA
<p>Disable some blocks</p>
DATA;
        return $html;
    }
}
