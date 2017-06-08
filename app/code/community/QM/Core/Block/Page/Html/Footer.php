<?php

class QM_Core_Block_Page_Html_Footer extends Mage_Page_Block_Html_Footer
{
    public function getLogoSrc()
    {
        $customLogoSrc = Mage::helper('qmcore/logo')->getFooterLogoSrc();

        return $customLogoSrc ? $customLogoSrc : null;
    }
}
