<?php

class QM_Core_Block_Adminhtml_Form_Renderer_Config_Fields
    extends Mage_Adminhtml_Block_Abstract
        implements Varien_Data_Form_Element_Renderer_Interface
{

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = <<<DATA
<p>Added new field types for systems configuration: </p>
<blockquote>
    <ol style="list-style: decimal; margin-left: 30px;">
        <li>Editor - textarea with wysiwyg</li>
    </ol>
</blockquote>
<br/>
<p>1. Instructions for "Editor": </p>
<ul style="list-style: disc; margin-left: 30px;">
    <li>set frontend_type to "editor"</li>
    <li>set frontend_model to "qmcore/adminhtml_system_config_editor"</li>
</ul>
DATA;
        return $html;
    }
}
