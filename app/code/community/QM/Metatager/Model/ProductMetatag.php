<?php

class QM_Metatager_Model_ProductMetatag extends QM_Metatager_Model_AbstractMetatag
{
    protected $_tableName;
    protected $_entityIdColumnName = 'product_id';
    protected $_entityName = 'product';
    protected $_metaKeywordField = 'meta_keyword';
    protected $_type = Mage_Catalog_Model_Product::ENTITY;

    public function _construct()
    {
        parent::_construct();

        $this->_tableName = Mage::getSingleton('core/resource')->getTableName('qm_metatager/product_metatag');
    }

    protected function _isReindexAll($storeId)
    {
        return $this->_helper->isReindexAllProducts($storeId);
    }

    protected function _isReindexOnlyActiveStore()
    {
        return $this->_helper->isReindexProductOnlyActiveStore();
    }

    protected function _getEntityModel()
    {
        return Mage::getModel('catalog/product');
    }

    protected function _getEntityTrackedAttr($storeId)
    {
        return $this->_helper->getProductTrackedAttr($storeId);
    }

    protected function _getEntityMetaOneUserPostValues()
    {
        return new Varien_Object(Mage::app()->getRequest()->getPost('product'));
    }

    protected function _getEntityMetaManyUserPostValues()
    {
        return new Varien_Object(Mage::app()->getRequest()->getPost('attributes'));
    }

    protected function _generateEntityAttrs($attributeCodes, $entityId, $storeId)
    {
        return $this->_ruleHelper->generateProductAttrs($attributeCodes, $entityId, $storeId);
    }
}