<?php

class QM_ExportCatalog_Model_Config extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('qm_exportcatalog/config');
    }

    public function getExportCatalogsArray()
    {
        $json = parent::getExportCatalogs();

        $catalogs = json_decode($json);

        return $catalogs ? $catalogs : array();
    }

    public function isProductValid(Mage_Catalog_Model_Product $product)
    {
        $isStockOk    = $this->getAddNotInStock() || $product->isInStock();
        $isMinPriceOk = !$this->getMinPrice() || $this->getMinPrice() <= $product->getPrice();
        $isMaxPriceOk = !$this->getMaxPrice() || $product->getPrice() <= $this->getMaxPrice();
        $isMinQtyOk   = !$this->getMinQty() || $this->getMinQty() <= $product->getStockItem()->getQty();

        return $isStockOk && $isMinPriceOk && $isMaxPriceOk && $isMinQtyOk;
    }

} 