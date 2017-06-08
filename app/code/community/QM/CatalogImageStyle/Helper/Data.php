<?php

class QM_CatalogImageStyle_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $_rgb;

    public function getBackgroundRgb()
    {
        if (!$this->_rgb) {
            $hex = Mage::getStoreConfig('qm_catalogimagestyle/background/hex');
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