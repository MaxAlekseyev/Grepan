<?php

class QM_Metatager_Model_Indexer_ProductImageAlt extends QM_Metatager_Model_Indexer_Abstract
{
    protected $_indexerId = 'qm_metatager_product_image_alt_metatag';
    protected $_matchedEntities = array(
        Mage_Catalog_Model_Product::ENTITY => array(
            Mage_Index_Model_Event::TYPE_SAVE
        ),
    );
    protected $_generateModel;
    protected $_name = 'QM Metatager Product Image Alt Indexer';
    protected $_description = 'Reindex Products Image Alts';
    protected $_entityName = 'product_image_alt';
    protected $_entity = Mage_Catalog_Model_Product::ENTITY;

    public function _construct()
    {
        $this->_generateModel = Mage::getModel('qm_metatager/productImageAlt');
        parent::_construct();
    }

    protected function _isAddOnSave($storeId)
    {
        return $this->_helper->isAddImageAltOnProductSave($storeId);
    }
}
