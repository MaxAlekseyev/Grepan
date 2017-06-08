<?php

class QM_Metatager_Model_ProductImageAlt extends QM_Metatager_Model_Abstract
{
    protected $_type = Mage_Catalog_Model_Product::ENTITY;
    protected $_tableName;
    protected $_productCache = array();

    protected function _construct()
    {
        parent::_construct();

        $this->_tableName = Mage::getSingleton('core/resource')->getTableName('qm_metatager/product_image_alt');
    }

    protected function _getProduct($productId, $storeId)
    {
        $key = $productId . '_' . $storeId;
        if (!isset($this->_productCache[$key])) {
            $this->_productCache[$key] = Mage::getResourceModel('catalog/product_collection')
                ->addAttributeToFilter('entity_id', $productId)
                ->addStoreFilter($storeId)->getFirstItem()->load('media_gallery');
        }

        return $this->_productCache[$key];
    }

    protected function _getProductImageValueIds($productId, $storeId)
    {
        $product = $this->_getProduct($productId, $storeId);

        return array_map(function ($data) {
            return $data['value_id'];
        }, $product->getMediaGallery()['images']);
    }

    protected function _getUserPostValues()
    {
        $post = Mage::app()->getRequest()->getPost('product')['media_gallery']['images'];
        $dataArray = Zend_Json::decode($post);
        
        if (!is_array($dataArray)) {
        	return new Varien_Object();
        }

        $values = array();

        foreach ($dataArray as $data) {
            $values[$data['value_id']] = $data['label'];
        }

        return new Varien_Object($values);
    }

    protected function _getTrackedAlt($valueId, $productId, $storeId)
    {
        $query = "SELECT label FROM {$this->_tableName} WHERE product_id={$productId} AND store_id={$storeId} AND value_id={$valueId}";

        $result = $this->_getReadAdapter()->fetchAll($query);

        return count($result) ? $result[0]['label'] : null;
    }

    protected function _isReindexAll($storeId)
    {
        return $this->_helper->isReindexAllProductImageAlt($storeId);
    }

    protected function _canGenerateAlt($valueId, $productId, $storeId, $userPostValues)
    {
        $trackedAttr = $this->_getTrackedAlt($valueId, $productId, $storeId);

        if (!$this->_isReindexAll($storeId) && !$trackedAttr) {
            return false;
        }

        $userValue = $userPostValues->getData($valueId);
        if ($userValue && $trackedAttr != $userValue) {
            return false;
        }

        return true;
    }

    protected function _isMainImage($valueId, $productId, $storeId)
    {
        $product = $this->_getProduct($productId, $storeId);
        $baseImage = $product->getImage();

        foreach ($product->getMediaGallery()['images'] as $data) {
            if ($data['value_id'] == $valueId) {
                return $baseImage == $data['file'];
            }
        }

        return false;
    }

    protected function _generateAlts($valueIds, $productId, $storeId)
    {
        $generated = array();

        $mainValueId = null;
        foreach ($valueIds as $valueId) {
            if ($this->_isMainImage($valueId, $productId, $storeId)) {
                $mainValueId = $valueId;
                break;
            }

        }

        $generated = $this->_ruleHelper->generateProductImageAlt($valueIds, $productId, $storeId, $mainValueId);
        $generated = array_map(function($alt){return addslashes($alt);}, $generated);

        return $generated;
    }


    /**
     * @param $data array array('prodId_storeId'=>array(value_id=>value)
     */
    protected function _saveGenerated($data)
    {
        if (!$this->_validateGeneratedData($data)) {
            return;
        }
        $preQuery = "INSERT INTO {$this->_tableName} (product_id,store_id,value_id,label) VALUES";
        $itemInsertions = array();
        $postQuery = "ON DUPLICATE KEY UPDATE label=VALUES(label);";

        foreach ($data as $prodIdStoreId => $generated) {
            list($productId, $storeId) = explode('_', $prodIdStoreId);

            foreach ($this->_getProductImageValueIds($productId, $storeId) as $valueId) {
                $alt = isset($generated[$valueId]) ? $generated[$valueId] : '';

                $itemInsertions []= "('{$productId}','{$storeId}','{$valueId}','{$alt}') ";
            }

        }

        try {
            $this->_getWriteAdapter()->query($preQuery . implode(', ', $itemInsertions) . $postQuery);
        } catch (Exception $e) {
            foreach ($itemInsertions as $itemInsertion) {
                try {
                    $this->_getWriteAdapter()->query($preQuery . $itemInsertion . $postQuery);
                } catch (Exception $ee) {
                    //Skip not exists items
                }
            }
        }
    }

    /**
     * @param $data array array('prodId_storeId'=>array(value_id=>value)
     */
    protected function _updateAlts($data)
    {
        if (!$this->_validateGeneratedData($data)) {
            return;
        }

        $query = "INSERT INTO catalog_product_entity_media_gallery_value (value_id,store_id,label) VALUES";

        $isFirst = true;
        foreach ($data as $productIdStoreId => $generated) {
            list($productId, $storeId) = explode('_', $productIdStoreId);

            foreach ($generated as $valueId => $alt) {
                if (!$isFirst) {
                    $query .= ',';
                }

                $query .= "('{$valueId}','{$storeId}','{$alt}') ";

                $isFirst = false;
            }
        }
        $query .= "ON DUPLICATE KEY UPDATE label=VALUES(label);";

        $this->_getWriteAdapter()->query($query);

        return $this;
    }

    public function generateOne($productId, $storeId)
    {
        $valueIds = $this->_getProductImageValueIds($productId, $storeId);
        $userPostValues = $this->_getUserPostValues();

        $valueIds = array_filter($valueIds, function ($valueId) use ($productId, $storeId, $userPostValues) {
            return $this->_canGenerateAlt($valueId, $productId, $storeId, $userPostValues);
        });

        if (count($valueIds)) {
            $generated = $this->_generateAlts($valueIds, $productId, $storeId);
        } else {
            $generated = array();
        }

        $this->_saveGenerated(array(($productId . '_' . $storeId) => $generated));

        $this->_updateAlts(array(($productId . '_' . $storeId) => $generated));
    }

    public function generateMany($productIds, $storeId)
    {
        $updateData = array();
        $userPostValues = new Varien_Object(); // no user data, generate

        foreach ($productIds as $productId) {
            $valueIds = $this->_getProductImageValueIds($productId, $storeId);

            $valueIds = array_filter($valueIds, function ($valueId) use ($productId, $storeId, $userPostValues) {
                return $this->_canGenerateAlt($valueId, $productId, $storeId, $userPostValues);
            });

            if (count($valueIds)) {
                $generated = $this->_generateAlts($valueIds, $productId, $storeId);
            } else {
                $generated = array();
            }

            $updateData[$productId . '_' . $storeId] = $generated;
        }

        $this->_saveGenerated($updateData);
        $this->_updateAlts($updateData);
    }

    protected function _getGeneratedEntityIds($storeId)
    {
        $query = "SELECT product_id FROM {$this->_tableName} WHERE store_id = {$storeId}";

        $result = $this->_getReadAdapter()->fetchAll($query);

        $ids = array();

        foreach ($result as $row) {
            $ids[] = $row[$this->_entityIdColumnName];
        }

        return array_unique($ids);
    }

    protected function _getEntityModel()
    {
        return Mage::getModel('catalog/product');
    }

    protected function _isReindexOnlyActiveStore()
    {
        return $this->_helper->isReindexProductImageAltOnlyActiveStore();
    }
}