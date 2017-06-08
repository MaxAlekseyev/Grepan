<?php

class QM_Core_Helper_Logo extends Mage_Core_Helper_Abstract
{

    public function getHeaderLogoSrc()
    {
        $headerLogoConf = $this->getHeaderLogo();

        if (!$headerLogoConf) {
            return '';
        }

        return Mage::getBaseUrl(QM_Core_Model_System_Config_Backend_Image_Logo::UPLOAD_ROOT) .
                QM_Core_Model_System_Config_Backend_Image_Logo::UPLOAD_DIR . DS .
                $headerLogoConf;
    }

    public function getFooterLogoSrc()
    {
        $footerLogoConf = $this->getFooterLogo();

        if (!$footerLogoConf) {
            return '';
        }

        return Mage::getBaseUrl(QM_Core_Model_System_Config_Backend_Image_Logo::UPLOAD_ROOT) .
                QM_Core_Model_System_Config_Backend_Image_Logo::UPLOAD_DIR . DS .
                $footerLogoConf;
    }

    public function getHeaderLogo()
    {
        return Mage::getStoreConfig('design/header/custom_header_logo');
    }

    public function getFooterLogo()
    {
        return Mage::getStoreConfig('design/footer/custom_footer_logo');
    }

    public function resizeLogo($type, $width, $height)
    {
        switch ($type) {
            case 'header': $fileName = $this->getHeaderLogo(); break;
            case 'footer': $fileName = $this->getFooterLogo(); break;
            default: $fileName = $this->getHeaderLogo(); break;
        }
        if($fileName) {
            return Mage::helper('qmcore')->getResizedImage("logo", "logo/cache", $fileName, $width.'x'.$height.$fileName, $width, $height);
        }
        return '';
    }
}