<?php

abstract class QM_Switcher_Block_Adminhtml_System_Attribute_After_Abstract extends Mage_Adminhtml_Block_Template
{
    /**
     * @var QM_Switcher_Block_Adminhtml_System_Attribute_Renderer
     */
    protected $_rendererInstance;
    /**
     * @var Mage_Catalog_Model_Resource_Eav_Attribute
     */
    protected $_attributeInstance;

    /**
     * @param QM_Switcher_Block_Adminhtml_System_Attribute_Renderer $rendererInstance
     * @return $this
     */
    public function setRendererInstance(QM_Switcher_Block_Adminhtml_System_Attribute_Renderer $rendererInstance)
    {
        $this->_rendererInstance = $rendererInstance;
        return $this;
    }

    /**
     * @return QM_Switcher_Block_Adminhtml_System_Attribute_Renderer
     */
    public function getRendererInstance()
    {
        return $this->_rendererInstance;
    }

    /**
     * @param Mage_Catalog_Model_Resource_Eav_Attribute $attributeInstance
     * @return $this
     */
    public function setAttributeInstance(Mage_Catalog_Model_Resource_Eav_Attribute $attributeInstance)
    {
        $this->_attributeInstance = $attributeInstance;
        return $this;
    }

    /**
     * @return Mage_Catalog_Model_Resource_Eav_Attribute
     */
    public function getAttributeInstance()
    {
        return $this->_attributeInstance;
    }

    /**
     * @return mixed
     */
    public function getDisabled()
    {
        return $this->getRendererInstance()->getDisabled();
    }

    /**
     * @return string
     */
    public function getHtmlPrefix()
    {
        return $this->getRendererInstance()->getHtmlId();
    }

    /**
     * @return mixed
     */
    public function getAttributeId()
    {
        return $this->getAttributeInstance()->getId();
    }
}
