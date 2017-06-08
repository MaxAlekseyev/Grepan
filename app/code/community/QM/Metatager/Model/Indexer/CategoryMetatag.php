<?php

class QM_Metatager_Model_Indexer_CategoryMetatag extends QM_Metatager_Model_Indexer_Abstract
{
    protected $_indexerId = 'qm_metatager_category_metatag';
    protected $_matchedEntities = array(
        Mage_Catalog_Model_Category::ENTITY => array(
            Mage_Index_Model_Event::TYPE_SAVE
        ),
    );
    protected $_generateModel;
    protected $_name = 'QM Metatager Category Metatag Indexer';
    protected $_description = 'Reindex Categorys Metatags';
    protected $_entityName = 'category';
    protected $_entity = Mage_Catalog_Model_Category::ENTITY;

    public function _construct()
    {
        $this->_generateModel = Mage::getModel('qm_metatager/categoryMetatag');
        parent::_construct();
    }

    protected function _isAddOnSave($storeId)
    {
        return $this->_helper->isAddMetaOnCategorySave($storeId);
    }
} 