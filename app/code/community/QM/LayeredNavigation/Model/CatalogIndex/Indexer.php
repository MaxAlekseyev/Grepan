<?php
class QM_LayeredNavigation_Model_CatalogIndex_Indexer extends Mage_CatalogIndex_Model_Indexer
{
    protected function _getProductCollection($store, $products) 
    {
        $products = parent::_getProductCollection($store, $products);
        
        $showInStockOnly = Mage::getStoreConfig('qm_layerednavigation/general/show_in_stock', $store);
        if ($showInStockOnly) {
            // add filter by stock
            Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
        }
        
        return $products;
    }
}