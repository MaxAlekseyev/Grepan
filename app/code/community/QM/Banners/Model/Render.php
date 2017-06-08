<?php

/**
 * Render model
 *
 * @category    QM
 * @package     QM_Banners
 *
 */
class QM_Banners_Model_Render extends Mage_Core_Model_Abstract
{
    const DEFAULT_STORE_RENDER_VALUE = '%default%';
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'qm_banners_render';
    const CACHE_TAG = 'qm_banners_render';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'qm_banners_render';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'render';

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
        $this->_init('qm_banners/render');
    }

    /**
     * before save banner
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
     * save banner relation
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
     * return mixed parent banner
     * @return mixed
     */
    protected function _getParentBanner()
    {
        return Mage::getModel('qm_banners/banner')->load($this->getBannerId());
    }

    /**
     * If some data (alt or offset not exists then return data from default renders)
     *
     * @return array array(imageId => array(alt,path,offset))
     *
     */
    public function getImageData()
    {
        if (!$this->getData('image_data')) {
            $json = $this->getImages();

            if ($json) {
                $imageDataCollection = Zend_Json::decode($this->getImages());
                $defaultRender       = $this->_getParentBanner()->getDefaultRender();

                if ($this->getId() != $defaultRender->getId()) { // it is not default render
                    $defaultRenderData = new Varien_Object($defaultRender->getImageData());

                    foreach ($defaultRender->getImageData() as $defImageId => $defImageData) {
                        if (!isset($imageDataCollection[$defImageId])) {
                            $imageDataCollection[$defImageId] = $defImageData;
                            continue;
                        }

                        if ((isset($imageDataCollection[$defImageId]['exclude']) && $imageDataCollection[$defImageId]['exclude']) || (isset($defImageData['exclude']) && $defImageData['exclude'])) {
                            continue;
                        }

                        if (!isset($imageDataCollection[$defImageId]['alt']) || !$imageDataCollection[$defImageId]['alt']) {
                            $imageDataCollection[$defImageId]['alt'] = $defaultRenderData->getData($defImageId . '/alt');
                        }

                        if (!isset($imageDataCollection[$defImageId]['offset']) || !$imageDataCollection[$defImageId]['offset']) {
                            $imageDataCollection[$defImageId]['offset'] = $defaultRenderData->getData($defImageId . '/offset');
                        }
                    }
                }
                $this->setData('image_data', $imageDataCollection);
            } else {
                $this->setData('image_data', array());
            }

        }
        return $this->getData('image_data');
    }

    /**
     * @param $imageId
     * @return QM_Banners_Model_Image or Varien_Object
     */
    protected function _getImageByImageId($imageId)
    {
        $data = $this->getImageData();

        if (!isset($data[$imageId])) {
            return new Varien_Object();
        }

        return $this->_getParentBanner()->getImageByImageId($imageId);

    }

    /**
     * Remove image from render
     * @param $image
     * @throws Exception
     */
    public function removeImage($image)
    {
        $data = $this->getImageData();

        unset($data[$image->getImageId()]);

        $this->setImages(Zend_Json::encode($data));
        $this->save();
    }

    //QTODO
    /**
     * Check is use default render setup
     *
     * @return bool
     */
    public function isUseDefaultHtmlRender()
    {
        return $this->getData('html_render') == self::DEFAULT_STORE_RENDER_VALUE;
    }

    //QTODO
    /**
     * Check is use default render setup
     *
     * @return bool
     */
    public function isUseDefaultRenderCss()
    {
        return $this->getData('css_style') == self::DEFAULT_STORE_RENDER_VALUE;
    }

    /**
     * @return string html render
     */
    public function getHtmlRender()
    {
        if ($this->isUseDefaultHtmlRender()) {
            return $this->_getParentBanner()->getDefaultRender()->getHtmlRender();
        } else {
            return parent::getHtmlRender();
        }
    }

    /**
     * @return string html render
     */
    public function getCssStyle()
    {
        if ($this->isUseDefaultRenderCss()) {
            return $this->_getParentBanner()->getDefaultRender()->getCssStyle();
        } else {
            return parent::getCssStyle();
        }
    }

    /**
     * Check if image not excluded from render
     * @param $imageId
     * @return bool
     */
    public function isUseImage($imageId)
    {
        if (!$this->getImages()) { //not created render
            return true;
        }
        $renderData = $this->getImageData();
        return isset($renderData[$imageId]['exclude']) && !$renderData[$imageId]['exclude'];
    }

    /**
     * @param $html string
     * @return string html with replaced src tag to image src
     */
    protected function _replaceSrc($html)
    {
        return preg_replace_callback(QM_Banners_Helper_Data::IMAGE_BY_INDEX_REGEXP, function ($matches) {
            $imageId = $matches[1];

            if ($this->isUseImage($imageId)) {
                return $this->_getImageByImageId($imageId)->getUrl();
            } else {
                return '';
            }

        }, $html);
    }

    /**
     * @param $html string
     * @return string html with replaced alt tag to image alt
     */
    protected function _replaceAlt($html)
    {
        return preg_replace_callback(QM_Banners_Helper_Data::ALT_BY_INDEX_REGEXP, function ($matches) {
            $imageId = $matches[1];

            if ($this->isUseImage($imageId)) {
                $imageData = new Varien_Object($this->getImageData());
                return $imageData->getData($imageId . '/alt');
            } else {
                return '';
            }

        }, $html);
    }

    /**
     * Sorted by image offset
     * @return $imageCollection Varien_Data_Collection
     */
    protected function _getSortedByOffsetImageCollection()
    {
        $imageData = $this->getImageData();

        uasort($imageData, function ($a, $b) {
            $a = new Varien_Object($a);
            $b = new Varien_Object($b);
            return $a->getOffset() - $b->getOffset();
        });

        $imageCollection = new Varien_Data_Collection();
        foreach ($imageData as $imageId => $data) {
            $data = new Varien_Object($data);
            if (!$data->getExclude()) {
                $imageCollection->addItem($this->_getImageByImageId($imageId));
            }
        }

        return $imageCollection;
    }

    /**
     * @param $html string
     * @return string html with proceeded loop by images
     */
    public function _proceedLoop($html)
    {
        return preg_replace_callback(QM_Banners_Helper_Data::FOREACH_BODY_REGEXP, function ($matches) {
            $body     = $matches[1];
            $rendered = '';

            $imageCollection = $this->_getSortedByOffsetImageCollection();

            foreach ($imageCollection as $image) {
                $renderedLoop = $body;
                $renderedLoop = preg_replace(QM_Banners_Helper_Data::FOREACH_ALT_INDEX_REGEXP,'{{alt[' . $image->getImageId() . ']}}' , $renderedLoop);
                $renderedLoop = preg_replace(QM_Banners_Helper_Data::FOREACH_IMAGE_INDEX_REGEXP,'{{image[' . $image->getImageId() . ']}}' , $renderedLoop);
                $rendered .= $renderedLoop;
            }
            return $rendered;
        }, $html);
    }

    /**
     * @return string html after render
     */
    public function getRenderedHtml()
    {
        if (!$this->getData('rendered_html')) {
            $html = $this->getHtmlRender();

            $html = $this->_proceedLoop($html);

            $html = $this->_replaceSrc($html);

            $html = $this->_replaceAlt($html);

            $this->setData('rendered_html', $html);
        }

        return $this->getData('rendered_html');
    }
}
