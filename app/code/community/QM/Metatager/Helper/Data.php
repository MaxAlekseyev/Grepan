<?php

class QM_Metatager_Helper_Data extends Mage_Core_Helper_Abstract
{
    const LOG_FILE_NAME = 'qm_metatager.log';
    const LOG_ERROR_FILE_NAME = 'qm_metatager_error.log';
    const META_TITLE = 'meta_title';
    const META_DESCRIPTION = 'meta_description';
    const META_KEYWORD_PRODUCT = 'meta_keyword';
    const META_KEYWORD_CATEGORY = 'meta_keywords';

    protected $_logEnabled = null;
    protected $_productImageAltRules;
    protected $_productImageAltRulesCount;

    public function getProductAttrRule($attrName, $storeId)
    {
        return Mage::getStoreConfig(
            "qm_metatager/product/$attrName",
            $storeId
        );
    }

    public function getCategoryAttrRule($attrName, $storeId)
    {
        return Mage::getStoreConfig(
            "qm_metatager/category/$attrName",
            $storeId
        );
    }

    public function isReindexAllProducts($storeId)
    {
        return Mage::getStoreConfig(
            'qm_metatager/product/reindex_all',
            $storeId
        );
    }

    public function isReindexAllCategory($storeId)
    {
        return Mage::getStoreConfig(
            'qm_metatager/category/reindex_all',
            $storeId
        );
    }

    public function isReindexAllProductImageAlt($storeId)
    {
        return Mage::getStoreConfig(
            'qm_metatager/product_image/reindex_all',
            $storeId
        );
    }

    public function isReindexProductOnlyActiveStore()
    {
        return Mage::getStoreConfig(
            'qm_metatager/product/reindex_only_active_store'
        );
    }

    public function isReindexCategoryOnlyActiveStore()
    {
        return Mage::getStoreConfig(
            'qm_metatager/category/reindex_only_active_store'
        );
    }

    public function isReindexProductImageAltOnlyActiveStore()
    {
        return Mage::getStoreConfig(
            'qm_metatager/product_image/reindex_only_active_store'
        );
    }

    public function isAddMetaOnProductSave($storeId)
    {
        return Mage::getStoreConfig(
            'qm_metatager/product/add_on_save',
            $storeId
        );
    }

    public function isAddMetaOnCategorySave($storeId)
    {
        return Mage::getStoreConfig(
            'qm_metatager/category/add_on_save',
            $storeId
        );
    }

    public function isAddImageAltOnProductSave($storeId)
    {
        return Mage::getStoreConfig(
            'qm_metatager/product_image/add_on_save',
            $storeId
        );
    }

    public function getProductTrackedAttr($storeId = 0)
    {
        $attributes = array();

        if (Mage::getStoreConfig('qm_metatager/product/meta_title_enable', $storeId)) {
            array_push($attributes, self::META_TITLE);
        }

        if (Mage::getStoreConfig('qm_metatager/product/meta_description_enable', $storeId)) {
            array_push($attributes, self::META_DESCRIPTION);
        }

        if (Mage::getStoreConfig('qm_metatager/product/meta_keyword_enable', $storeId)) {
            array_push($attributes, self::META_KEYWORD_PRODUCT);
        }

        return $attributes;
    }

    public function getCategoryTrackedAttr()
    {
        $attributes = array();

        if (Mage::getStoreConfig('qm_metatager/category/meta_title_enable')) {
            array_push($attributes, self::META_TITLE);
        }

        if (Mage::getStoreConfig('qm_metatager/category/meta_description_enable')) {
            array_push($attributes, self::META_DESCRIPTION);
        }

        if (Mage::getStoreConfig('qm_metatager/category/meta_keywords_enable')) {
            array_push($attributes, self::META_KEYWORD_CATEGORY);
        }

        return $attributes;
    }

    public function getProductImageAltRule($storeId, $index)
    {
        if (!$this->_productImageAltRules) {
            $rulesString = Mage::getStoreConfig(
                'qm_metatager/product_image/alt_rules',
                $storeId
            );

            $this->_productImageAltRules = explode(PHP_EOL, $rulesString);
            $this->_productImageAltRulesCount = count($this->_productImageAltRules);
        }

        if (!$this->_productImageAltRulesCount) {
            return '';
        }

        return $this->_productImageAltRules[$index % $this->_productImageAltRulesCount];
    }

    public function getProductMainImageAltRule($storeId)
    {
        return Mage::getStoreConfig(
            'qm_metatager/product_image/main_image_alt',
            $storeId
        );
    }
}