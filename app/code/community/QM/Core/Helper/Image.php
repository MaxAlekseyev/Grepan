<?php

class QM_Core_Helper_Image extends Mage_Catalog_Helper_Image
{
    protected $_defaultBackground = array(255, 255, 255);
    protected $_rgb;

    public function resize($width, $height = null)
    {
        if (!$this->_isCustomBackground()) {
            $this->backgroundColor($this->_getBackgroundRgb());
        }

        return parent::resize($width, $height);
    }

    protected function _isCustomBackground()
    {
        return $this->_defaultBackground != $this->_getModel()->getBackgroundColor();
    }

    protected function _getBackgroundRgb()
    {
        if (!$this->_rgb) {
            $hex = Mage::getStoreConfig('qmcore/catalog_image_style/background_hex');
            $this->_rgb = $this->_hexToRgb($hex);
        }

        return $this->_rgb;
    }

    protected function _hexToRgb($hex)
    {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array($r, $g, $b);

        return $rgb;
    }
}