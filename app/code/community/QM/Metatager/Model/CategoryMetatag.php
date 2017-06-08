<?php

class QM_Metatager_Model_CategoryMetatag extends QM_Metatager_Model_AbstractMetatag
{
    protected $_tableName;
    protected $_entityIdColumnName = 'category_id';
    protected $_entityName = 'category';
    protected $_metaKeywordField = 'meta_keywords';
    protected $_type = Mage_Catalog_Model_Category::ENTITY;

    public function _construct()
    {
        parent::_construct();

        $this->_tableName = Mage::getSingleton('core/resource')->getTableName('qm_metatager/category_metatag');
    }

    protected function _isReindexAll($storeId)
    {
        return $this->_helper->isReindexAllCategory($storeId);
    }

    protected function _isReindexOnlyActiveStore()
    {
        return $this->_helper->isReindexCategoryOnlyActiveStore();
    }

    protected function _getEntityModel()
    {
        return Mage::getModel('catalog/category');
    }

    protected function _getEntityTrackedAttr($storeId)
    {
        return $this->_helper->getCategoryTrackedAttr($storeId);
    }

    protected function _getEntityMetaOneUserPostValues()
    {
        return new Varien_Object(Mage::app()->getRequest()->getPost('general'));
    }

    protected function _getEntityMetaManyUserPostValues()
    {
        return new Varien_Object();
    }

    protected function _generateEntityAttrs($attributeCodes, $entityId, $storeId)
    {
        return $this->_ruleHelper->generateCategoryAttrs($attributeCodes, $entityId, $storeId);
    }
}