<?php

class QM_Metatager_Helper_Rule extends Mage_Core_Helper_Abstract
{
    const ATTR_REGEXP = '/{{(.+)}}/U';
    protected $_helper;

    public function __construct()
    {
        $this->_helper = Mage::helper('qm_metatager');
    }

    /***
     * @param $codeArray array array of codes for generate
     * @param $entityId
     * @param $storeId
     * @return array array(code=>value)
     */
    public function generateProductAttrs($codeArray, $entityId, $storeId)
    {
        $ruleArray = array();

        foreach ($codeArray as $code) {
            $ruleArray[$code] = $this->_helper->getProductAttrRule($code, $storeId);
        }

        return $this->_proceedRules($ruleArray, $entityId, $storeId, 'product');
    }

    /***
     * @param $codeArray array codes for generate
     * @param $entityId
     * @param $storeId
     * @return array array(code=>value)
     */
    public function generateCategoryAttrs($codeArray, $entityId, $storeId)
    {
        $ruleArray = array();

        foreach ($codeArray as $code) {
            $ruleArray[$code] = $this->_helper->getCategoryAttrRule($code, $storeId);
        }

        return $this->_proceedRules($ruleArray, $entityId, $storeId, 'category');
    }

    public function generateProductImageAlt($valueIds, $productId, $storeId, $mainValueId)
    {
        $ruleArray = array();

        $i = 0;
        foreach ($valueIds as $valueId) {
            if ($valueId === $mainValueId) {
                $ruleArray[$valueId] = $this->_helper->getProductMainImageAltRule($storeId);
            } else {
                $ruleArray[$valueId] = $this->_helper->getProductImageAltRule($storeId, $i);
                $i++;
            }
        }

        return $this->_proceedRules($ruleArray, $productId, $storeId, 'product');
    }

    protected function _findUsedAttrCodes($ruleArray)
    {
        $ruleCodes = array_map(function ($rule) {
            $ret = array();
            preg_match_all(self::ATTR_REGEXP, $rule, $ret);
            return $ret[1];
        }, $ruleArray);

        $ruleCodes = iterator_to_array(
            new RecursiveIteratorIterator(new RecursiveArrayIterator($ruleCodes)),
            FALSE);

        return array_unique($ruleCodes);
    }

    protected function _proceedRules($ruleArray, $entityId, $storeId, $entityName)
    {
        $entity = Mage::getResourceModel('catalog/' . $entityName . '_collection')
            ->addAttributeToFilter('entity_id', $entityId);
        if ($entityName == 'product') {
            $entity->addStoreFilter($storeId);
        } else {
            $entity->setStoreId($storeId);
        }


        $usedAttr = $this->_findUsedAttrCodes($ruleArray);
        foreach ($usedAttr as $code) {
            $entity->addAttributeToSelect($code);
        }
        $entity = $entity->getFirstItem();

        $proceeded = array();
        foreach ($ruleArray as $code => $rule) {
            $proceeded[$code] = preg_replace_callback(self::ATTR_REGEXP, function ($matchArray) use ($entity, $storeId, $entityName) {
                if ($matchArray[1] == 'category_names' && $entityName == 'product') {
                    $categoryNames = array();

                    $collection = Mage::getResourceModel('catalog/category_collection')->setStoreId($storeId)
                        ->addAttributeToSelect('name')
                        ->addAttributeToFilter(array(
                            array(
                                'attribute' => 'entity_id',
                                'in'        => $entity->getCategoryIds(),
                            )
                        ));

                    foreach ($collection as $category) {
                        $categoryNames[] = $category->getName();
                    }

                    return implode(',', $categoryNames);
                } else {
                    return $entity->getData($matchArray[1]);
                }
            }, $rule);
        }

        return $proceeded;
    }
}