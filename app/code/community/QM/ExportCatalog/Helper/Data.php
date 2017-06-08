<?php

class QM_ExportCatalog_Helper_Data extends Mage_Core_Helper_Abstract
{
    const LEFT_DELIMITER = '%';
    const RIGHT_DELIMITER = '%';
    const LOG_FILE_NAME = 'export_catalog.log';
    const LAST_ERRORS_LOG_FILE_NAME = 'export_catalog_last_errors.log';

    const OFFER_TYPE_SIMPLE = 0;
    const OFFER_TYPE_VENDOR_MODEL = 1;
    const OFFER_TYPE_SMART = 2;

    public function getExportDir()
    {
        return Mage::getBaseDir() . '/' . Mage::getStoreConfig('qm_exportcatalog/qm_exportcatalog_settings/file_path') . '/';
    }

    public function getProductModelAttrCode()
    {
        return Mage::getStoreConfig('qm_exportcatalog/qm_exportcatalog_settings/product_model_attribute');
    }

    public function getProductModel(Mage_Catalog_Model_Product $product)
    {
        $code = $this->getProductModelAttrCode();

        $attr = $product->getResource()->getAttribute($code);

        if ($attr) {
            return $attr->getFrontend()->getValue($product);
        }
    }

    public function getProductVendorAttrCode()
    {
        return Mage::getStoreConfig('qm_exportcatalog/qm_exportcatalog_settings/product_vendor_attribute');
    }

    public function getProductVendor(Mage_Catalog_Model_Product $product)
    {
        $code = $this->getProductVendorAttrCode();

        $attr = $product->getResource()->getAttribute($code);

        if ($attr) {
            return $attr->getFrontend()->getValue($product);
        }
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @return mixed
     */
    public function getProductSalesNotes(Mage_Catalog_Model_Product $product)
    {
        $rule = Mage::getStoreConfig("qm_exportcatalog/qm_exportcatalog_settings/sales_notes_rule");

        return $this->_proceedRule($rule, $product);
    }

    /**
     * Get proceeding rule result
     * @param $rule
     * @param Mage_Catalog_Model_Product $product
     * @return mixed
     */
    protected function _proceedRule($rule, Mage_Catalog_Model_Product $product)
    {
        $value = preg_replace_callback('/\\' . self::LEFT_DELIMITER . '(.*?)\\' . self::RIGHT_DELIMITER . '/', function ($matchArray) use ($product) {
            return $this->_getProductAttrValue($matchArray[1], $product);
        }, $rule);

        return $value;
    }

    private function _getProductAttrValue($match, Mage_Catalog_Model_Product $product)
    {
        $value = null;

        $attribute = $product->getResource()->getAttribute($match);
        if ($attribute) {
            $attr = $attribute->getFrontend()->getValue($product);

            if ($attr) { // for example if empty array return null, not 'Array'
                $value = $attr;
            }
        }

        return $value;
    }

    public function isAddRelated()
    {
        return Mage::getStoreConfig('qm_exportcatalog/qm_exportcatalog_settings/add_related');
    }

    public function getOfferType()
    {
        return Mage::getStoreConfig('qm_exportcatalog/qm_exportcatalog_settings/offer_type');
    }

    public function isExportOnsave()
    {
        return Mage::getStoreConfig('qm_exportcatalog/qm_exportcatalog_settings/export_onsave');
    }

    public function error($message)
    {
        Mage::log($message, 3, self::LAST_ERRORS_LOG_FILE_NAME);
        Mage::log($message, 3, self::LOG_FILE_NAME);
    }

    public function log($message)
    {
        Mage::log($message, null, self::LOG_FILE_NAME);
    }

    public function errorForProduct($message, Mage_Catalog_Model_Product $product)
    {
        $this->error('Error ' . $message . ' for product with id ' . $product->getId());
    }

    public function getConfigUrl($configId)
    {
        return Mage::getUrl('ymlcatalog/get/file',
            array(
                '_store' => Mage::app()->getDefaultStoreView()->getId(),
                'id' => $configId)
        );
    }
}