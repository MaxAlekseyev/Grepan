<?php

class QM_OneclickOrder_Helper_Data extends Mage_Core_Helper_Abstract
{
    const REFERER_QUERY_PARAM_NAME = 'referer';

    public function convertOptions($options)
    {
        $converted = array();
        foreach ($options as $option){
            if (isset($option['value']) && !is_array($option['value']) && isset($option['label']) && !is_array($option['label'])){
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }

    public function getPostActionUrl($route)
    {
        return $this->_getUrl($route, $this->getUrlParams());
    }

    public function getUrlParams()
    {
        $params = array();

        $referer = $this->_getRequest()->getParam(self::REFERER_QUERY_PARAM_NAME);

        if (!$referer) {
            $referer = Mage::helper('core/url')->getCurrentUrl();
            $referer = Mage::helper('core')->urlEncode($referer);
        }

        if ($referer) {
            $params = array(self::REFERER_QUERY_PARAM_NAME => $referer);
        }

        return $params;
    }

    public function getPhoneValidationPattern()
    {
        return '/^\+\d{2}\(\d{3}\)\d{3}-\d{2}-\d{2}$/';
    }
}
