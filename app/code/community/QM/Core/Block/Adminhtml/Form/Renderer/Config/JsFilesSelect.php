<?php

class QM_Core_Block_Adminhtml_Form_Renderer_Config_JsFilesSelect
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_values;
    protected $_name;

    /**
     * @return mixed
     */
    public function getValues()
    {
        return $this->_values;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    public function isSelected($opt)
    {
        return isset($opt['selected']) && $opt['selected'];
    }

    public function __construct()
    {
        $this->setTemplate('qmcore/form/renderer/config/js_files_select.phtml');
    }

    protected function _sort($options, $configs)
    {
        if (!$configs || (count($configs) == 1 && !$configs[0])) {
            return $options;
        }
        $notTrackedOpts = array();
        $sortedOptions = array();
        foreach ($options as $opt) {
            $isTracked = false;
            foreach ($configs as $i => $conf) {
                if ($conf['value'] == $opt['value']) {
                    $opt['selected']   = $conf['selected'];
                    $sortedOptions[$i] = $opt;
                    $isTracked = true;
                    break;
                }
            }

            if (!$isTracked) {
                $opt['selected'] = false;
                $notTrackedOpts[]= $opt;
            }

        }
        ksort($sortedOptions);
        return array_merge($sortedOptions, $notTrackedOpts);
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $configs = Mage::helper('qmcore')->getFilesJsConfig();

        $this->_values = $this->_sort($element->getValues(), $configs);
        $this->_name   = $element->getName();

        return $this->toHtml();
    }
}
