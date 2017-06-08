<?php
class QM_Core_Block_Adminhtml_Form_Renderer_Config_Ifconfig
    extends Mage_Adminhtml_Block_Abstract
        implements Varien_Data_Form_Element_Renderer_Interface
{

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = <<<DATA
<p>You can use this extension in 3 mode:</p>
<blockquote>
    <ol style="list-style: decimal; margin-left: 30px;">
        <li>Magento default mode</li>
        <li>Extended default mode</li>
        <li>Flexible professional mode</li>
    </ol>
</blockquote>
<br />
<p>1. This extension is completely compatible with Magento\'s default action. So don\'t worry about it, all default ifconfigs used by Magento core team work like always without problem. Also you can use default ifconfig like before anywhere, anytime.</p>
<p>2. Extended default mode just adds a new "condition" attribute which will defines the condition for action method. Let\'s take a look at above examples. You want load a javascript file in frontend when value of Menu Type option is "animation" for your Professional Menu extension. You write action tag like this: ...action method="addJs" ifconfig="catalog/promenu/type" condition="animation"... The extension will gets value of "type" option in "promenu" module and compares it with condition attribute value, if it be equal to "animation" then it will calls addJs method.</p>
<p>3. In professional mode you can use multiple options of a module or multiple modules, with custom operation to check those. Let\'s describe it with an example. You want load a stylesheet file when your extension is active and style option is true in your extension setting or when value of skin for theme is equal to "modern". You must write something like this: ...action method="addCss" modules="general/anextension/; design/theme/" options="active, style; skin" conditions="true, 1; modern" operation="(0 and 1) or 2"... The ifconfig attribute explodes into two options, "modules" and "options". In modules attribute you define name of section and name of group which you defined for you modules configuration settings in its system.xml file, and you define option in options attribute with the name which you defined inside fields tag in system.xml file. So syntax is modules="section/group/" and options="option_name". If you want use multiple module, you must separate those with semicolon(;) in modules, options and conditions attributes; and if you want check for multiple options for each module, you must separate each option with comma(,). So in above example, the extension will gets value of active and style from general/anextension/ path from your extension, so they are general/anextension/active and general/anextension/style, and it gets value of skin field from design/theme path which is design/theme/skin. Now we must define conditions for each one of this options. Let\'s back to options attribute. The extension reads value of this attribute from left to right and marks each option with a number from zero, so active is 0, style is 1 and skin is 2. In condition attribute we must define condition of each option by its sort order. In above example, active option will checks for true value, style for true also and skin for modern value. The result of comparison will save in its option mark number. And finally we define our operation to let action to calls addCss. In operation attribute we use mark number of each option instead its name. In the example we said to extension, calls addCss if active option and style both are equal to true or skin option has modern value.</p>
<p><strong>Note:</strong></p>
<blockquote>
    <ul style="list-style: disc; margin-left: 30px;">
        <li>Don\'t use "ifconfig" attribute if you use flexible professional mode.</li>
        <li>Don\'t forget second slash in modules attribute.</li>
        <li>Separate each module with semicolon(;).</li>
        <li>Separate each module\'s option with comma(,).</li>
        <li>You must use both modules and options attributes.</li>
        <li>If conditions attribute isn\'t set or its value is null, true value will use for each option.</li>
        <li>If operation attribute isn\'t set or its value is null, options mark numbers will use from left to right, and "AND" operator will use for all of them.</li>
        <li>Conditions can be boolean, string and numeric.</li>
        <li>For true condition you can use true, TRUE or 1.</li>
        <li>For false condition you can use false, FALSE or 0.</li>
        <li>For and operator you can use and, AND or .(dot) symbol.</li>
        <li>For or operator you can use or, OR, +(plus) symbol.</li>
        <li>In operation you can use just integers, parentheses and specified operators.</li>
    </ul>
</blockquote>
DATA;
        return $html;
    }
}
