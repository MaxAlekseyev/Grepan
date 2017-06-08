<?php

class QM_Switcher_Block_Adminhtml_System_Attribute_After_Product_Image extends QM_Switcher_Block_Adminhtml_System_Attribute_After_Abstract
{
    /**
     * @var array
     */
    protected $_options;

    /**
     * @return Varien_Data_Form
     */
    public function getForm()
    {
        $form = new Varien_Data_Form();

        $form->addField(
            'product_image',
            'select',
            array(
                'name' => 'product_image',
                'label' => Mage::helper('qm_switcher')->__('Use this image attribute'),
                'options' => $this->getOptionsArray(),
                'disabled' => $this->getDisabled()
            )
        );
        $form->setHtmlIdPrefix($this->getHtmlPrefix().'_'.$this->getAttributeId());
        $form->setFieldNameSuffix($this->getRendererInstance()->getName().'[options]['.$this->getAttributeId().']');
        $form->setValues(array('product_image' => $this->getValue()));
        return $form;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        return $this->getForm()->toHtml();
    }

    /**
     * @return string
     */
    public function getValue()
    {
        $values = $this->getRendererInstance()->getValue();
        if (isset($values['options'][$this->getAttributeId()]['product_image'])) {
            return $values['options'][$this->getAttributeId()]['product_image'];
        }
        return '';
    }

    /**
     * @return mixed
     */
    public function getOptionsArray()
    {
        if (is_null($this->_options)) {
            $collection = Mage::getResourceModel('catalog/product_attribute_collection')
                ->addVisibleFilter()
                ->addFieldToFilter('frontend_input', 'media_image');
            foreach ($collection as $attribute) {
                /** @var Mage_Catalog_Model_Resource_Eav_Attribute $attribute */
                $this->_options[$attribute->getAttributeCode()] = $attribute->getFrontendLabel();
            }
        }
        return $this->_options;
    }

    /**
     * @return mixed
     */
    public function getDisabled()
    {
        return $this->getRendererInstance()->getDisabled();
    }
}
