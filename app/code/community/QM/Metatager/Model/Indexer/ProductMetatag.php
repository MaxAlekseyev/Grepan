<?php

class QM_Metatager_Model_Indexer_ProductMetatag extends QM_Metatager_Model_Indexer_Abstract
{
    protected $_indexerId = 'qm_metatager_product_metatag';
    protected $_matchedEntities = array(
        Mage_Catalog_Model_Product::ENTITY => array(
            Mage_Index_Model_Event::TYPE_SAVE
        ),
    );
    protected $_generateModel;
    protected $_name = 'QM Seo Product Metatag Indexer';
    protected $_description = 'Reindex Products Metatags';
    protected $_entityName = 'product';
    protected $_entity = Mage_Catalog_Model_Product::ENTITY;

    public function _construct()
    {
        $this->_generateModel = Mage::getModel('qm_metatager/productMetatag');
        parent::_construct();
    }

    protected function _isAddOnSave($storeId)
    {
        return $this->_helper->isAddMetaOnProductSave($storeId);
    }
} 