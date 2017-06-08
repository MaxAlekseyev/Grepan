<?php

/**
 * Image model
 *
 * @category    QM
 * @package     QM_Banners
 *
 */
class QM_Banners_Model_Image extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'qm_banners_image';
    const CACHE_TAG = 'qm_banners_image';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'qm_banners_image';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'image';

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
        $this->_init('qm_banners/image');
    }

    protected function _beforeDelete()
    {
        parent::_beforeDelete();

        $banner = Mage::getModel('qm_banners/banner')->load($this->getBannerId());


        foreach ($banner->getAllRenders() as $render) {
            $render->removeImage($this);
        }
    }

    /**
     * before save image
     *
     * @access protected
     * @return QM_Banners_Model_Render
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
     * save image relation
     *
     * @access public
     * @return QM_Banners_Model_Render
     *
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
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
     * @return string image full url
     */
    public function getUrl()
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $this->getOriginPath();
    }
}
