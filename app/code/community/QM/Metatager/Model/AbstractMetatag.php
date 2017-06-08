<?php

abstract class QM_Metatager_Model_AbstractMetatag extends QM_Metatager_Model_Abstract
{
    abstract protected function _isReindexAll($storeId);

    protected function _getTrackedEntityAttr($code, $entityId, $storeId)
    {
        $query = "SELECT {$code} FROM {$this->_tableName} WHERE {$this->_entityIdColumnName}={$entityId} AND store_id={$storeId}";

        $result = $this->_getReadAdapter()->fetchAll($query);

        return count($result) ? $result[0][$code] : null;
    }

    protected function _canGenerateEntityAttr($code, $entityId, $storeId, $userPostValues)
    {
        $trackedAttr = $this->_getTrackedEntityAttr($code, $entityId, $storeId);

        if (!$this->_isReindexAll($storeId) && !$trackedAttr) {
            return false;
        }

        $userValue = $userPostValues->getData($code);
        if ($userValue && $trackedAttr != $userValue) {
            return false;
        }

        return true;
    }

    protected function _saveGenerated($entityData)
    {
        if (!$this->_validateGeneratedData($entityData, true)) {
            return;
        }

        $preQuery = "INSERT INTO {$this->_tableName} ({$this->_entityIdColumnName},store_id,meta_title,{$this->_metaKeywordField},meta_description) VALUES ";
        $itemInsertions = array();
        $postQuery = "ON DUPLICATE KEY UPDATE meta_title=VALUES(meta_title), {$this->_metaKeywordField}=VALUES({$this->_metaKeywordField}),meta_description=VALUES(meta_description);";

        foreach ($entityData as $entityAndStoreId => $generated) {
            list($entityId, $storeId) = explode('_', $entityAndStoreId);

            $metaTitle = isset($generated['meta_title']) ? addslashes($generated['meta_title']) : '';
            $metaKeyword = isset($generated[$this->_metaKeywordField]) ? addslashes($generated[$this->_metaKeywordField]) : '';
            $metaDescription = isset($generated['meta_description']) ? addslashes($generated['meta_description']) : '';


            $itemInsertions []= "('{$entityId}','{$storeId}','{$metaTitle}','{$metaKeyword}','{$metaDescription}') ";
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

    protected function _getGeneratedEntityIds($storeId)
    {
        $query = "SELECT " . $this->_entityIdColumnName . " FROM {$this->_tableName} WHERE store_id = {$storeId}";

        $result = $this->_getReadAdapter()->fetchAll($query);

        $ids = array();

        foreach ($result as $row) {
            $ids[] = $row[$this->_entityIdColumnName];
        }

        return $ids;
    }


    /**
     * @param $entityData array array('entityId_catId'=>array('code'=>value));
     * @return $this
     * @throws Exception
     */
    protected function _updateAttributes($entityData)
    {
        $this->_getWriteAdapter()->beginTransaction();
        try {
            $i = 0;
            foreach ($entityData as $entityAndStoreId => $attrData) {
                $i++;
                list($entityId, $storeId) = explode('_', $entityAndStoreId);

                $object = new Varien_Object();
                $object->setIdFieldName('entity_id')
                    ->setStoreId($storeId);

                foreach ($attrData as $attrCode => $value) {
                    $attribute = $this->getAttribute($attrCode);
                    if (!$attribute->getAttributeId()) {
                        continue;
                    }
                    $object->setId($entityId);
                    $this->_saveAttributeValue($object, $attribute, $value);
                }
                if ($i % 1000 == 0) {
                    $this->_processAttributeValues();
                }
            }
            $this->_processAttributeValues();
            $this->_getWriteAdapter()->commit();
        } catch (Exception $e) {
            $this->_getWriteAdapter()->rollBack();
            throw $e;
        }

        return $this;
    }

    abstract protected function _getEntityTrackedAttr($storeId);
    abstract protected function _getEntityMetaManyUserPostValues();
    abstract protected function _generateEntityAttrs($attributeCodes, $entityId, $storeId);

    public function generateOne($entityId, $storeId)
    {
        $attributeCodes = $this->_getEntityTrackedAttr($storeId);
        $userPostValues = $this->_getEntityMetaOneUserPostValues();

        $attributeCodes = array_filter($attributeCodes, function ($code) use ($entityId, $storeId, $userPostValues) {
            return $this->_canGenerateEntityAttr($code, $entityId, $storeId, $userPostValues);
        });

        if (count($attributeCodes)) {
            $generated = $this->_generateEntityAttrs($attributeCodes, $entityId, $storeId);
        } else {
            $generated = array();
        }

        $this->_saveGenerated(array(($entityId . '_' . $storeId) =>$generated));

        $this->_updateAttributes(array(($entityId . '_' . $storeId) =>$generated));
    }

    abstract protected function _getEntityMetaOneUserPostValues();
    
    public function generateMany($entityIds, $storeId)
    {
        $userPostValues = $this->_getEntityMetaManyUserPostValues();

        $updateEntityData = array();

        foreach ($entityIds as $entityId) {
            $attributeCodes = $this->_getEntityTrackedAttr($storeId);

            $attributeCodes = array_filter($attributeCodes, function ($code) use ($entityId, $storeId, $userPostValues) {
                return $this->_canGenerateEntityAttr($code, $entityId, $storeId, $userPostValues);
            });

            if (count($attributeCodes)) {
                $generated = $this->_generateEntityAttrs($attributeCodes, $entityId, $storeId);
            } else {
                $generated = array();
            }

            $updateEntityData[$entityId . '_' . $storeId] = $generated;
        }

        $this->_saveGenerated($updateEntityData);
        $this->_updateAttributes($updateEntityData);
    }

}