<?php

/**
 * Banner model
 *
 * @category    QM
 * @package     QM_Banners
 *
 */
class QM_Banners_Model_Banner extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'qm_banners_banner';
    const CACHE_TAG = 'qm_banners_banner';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'qm_banners_banner';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'banner';

    /**
     * constructor
     *
     * @access public
     * @return void
     *
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('qm_banners/banner');
    }

    /**
     * before save banner
     *
     * @access protected
     * @return QM_Banners_Model_Banner
     *
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * before delete banner
     */
    protected function _beforeDelete()
    {
        parent::_beforeDelete();
        foreach ($this->getAllRenders() as $render) {
            $render->delete();
        }
        foreach ($this->getAllImages() as $image) {
            $image->delete();
        }
    }


    /**
     * get default values
     *
     * @access public
     * @return array
     *
     */
    public function getDefaultValues()
    {
        $values           = array();
        $values['status'] = 1;
        return $values;
    }

    /**
     * @return mixed render for default values
     */
    public function getDefaultRender()
    {
        return $this->getAllRenders()->addFieldToFilter('store_id', 0)->getFirstItem();
    }

    /**
     * Return render for store (not use default render if current not exist)
     * @param $storeId
     * @return mixed
     */
    public function getRealRenderForStore($storeId)
    {
        return $this->getAllRenders()->addFieldToFilter('store_id', $storeId)->getFirstItem();
    }

    /**
     * Return render for store if it no exists return render for default values
     * @param int $storeId store id
     * @return mixed all renders added to banner and in store with id
     */
    public function getRenderForStore($storeId)
    {
        $storeRender = $this->getAllRenders()->addFieldToFilter('store_id', $storeId)->getFirstItem();
        if ($storeRender->getId()) {
            return $storeRender;
        } else {
            return $this->getDefaultRender();
        }
    }

    /**
     * @return mixed all images added to banner
     */
    public function getAllImages()
    {
        return Mage::getModel('qm_banners/image')->getCollection()
            ->addFieldToFilter('banner_id', $this->getId());
    }

    /**
     * @return mixed all renders added to banner
     */
    public function getAllRenders()
    {
        return Mage::getModel('qm_banners/render')->getCollection()
            ->addFieldToFilter('banner_id', $this->getId());
    }

    /**
     * @return mixed
     */
    public function getRenderedHtml()
    {
        return $this->getRenderForStore(Mage::app()->getStore()->getId())->getRenderedHtml();
    }

    /**
     * @return mixed
     */
    public function getCssStyle()
    {
        return $this->getRenderForStore(Mage::app()->getStore()->getId())->getCssStyle();
    }

    /**
     * @param $imageId
     * @return mixed
     */
    public function getImageByImageId($imageId)
    {
        return $this->getAllImages()->addFilter('image_id', $imageId)->getFirstItem();
    }

}
