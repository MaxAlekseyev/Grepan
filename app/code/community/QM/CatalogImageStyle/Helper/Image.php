<?php

class QM_CatalogImageStyle_Helper_Image extends Mage_Catalog_Helper_Image
{
    protected $_defaultBackground = array(255, 255, 255);
    protected $_helper;

    public function __construct()
    {
        $this->_helper = Mage::helper('qm_catalogimagestyle');
    }

    public function resize($width, $height = null)
    {
        if (!$this->_isCustomBackground()) {
            $this->backgroundColor($this->_helper->getBackgroundRgb());
        }

        return parent::resize($width, $height);
    }

    protected function _isCustomBackground()
    {
        return $this->_defaultBackground != $this->_getModel()->getBackgroundColor();
    }

}