<?php

class QM_Core_Block_Page_Html_Header extends Mage_Page_Block_Html_Header
{
    public function getLogoSrc()
    {
        $customLogoSrc = Mage::helper('qmcore/logo')->getHeaderLogoSrc();

        return $customLogoSrc ? $customLogoSrc : parent::getLogoSrc();
    }
}
