<?php

class QM_Banners_Block_Adminhtml_Banner_Helper_Render_Content extends Mage_Adminhtml_Block_Widget
{
    protected $_banner;
    protected $_storeId;
    protected $_storeRender;

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('qm/banners/helper/render.phtml');
    }

    public function setBanner($banner)
    {
        $this->_banner = $banner;

        return $this;
    }

    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStoreId()
    {
        return $this->_storeId;
    }

    /**
     * @return mixed
     */
    public function getBanner()
    {
        return $this->_banner;
    }

    public function getImageCollection()
    {
        return $this->_banner->getAllImages();
    }

    protected function _getStoreRender()
    {
        if (!$this->_storeRender) {
            $this->_storeRender = $this->_banner->getRenderForStore($this->_storeId);
        }
        return $this->_storeRender;
    }

    public function getImageAlt($imageId)
    {
        return $this->isUseImageInStore($imageId) ? $this->_getStoreRender()->getImageData()[$imageId]['alt'] : '';
    }

    public function getImageOffset($imageId)
    {
        return $this->isUseImageInStore($imageId) ? $this->_getStoreRender()->getImageData()[$imageId]['offset'] : '';
    }

    public function isUseImageInStore($imageId)
    {
        return $this->_banner->getRealRenderForStore($this->_storeId)->isUseImage($imageId);
    }

    public function getHtmlRender()
    {
        return $this->_getStoreRender()->getHtmlRender();
    }

    public function getRenderCss()
    {
        return $this->_getStoreRender()->getCssStyle();
    }

    public function isUseDefaultHtmlRender()
    {
        if ($this->_banner->getRealRenderForStore($this->_storeId)->getId()) {
            return $this->_getStoreRender()->isUseDefaultHtmlRender();
        }
        return true;
    }

    public function isUseDefaultRenderCss()
    {
        if ($this->_banner->getRealRenderForStore($this->_storeId)->getId()) {
            return $this->_getStoreRender()->isUseDefaultRenderCss();
        }
        return true;
    }
}