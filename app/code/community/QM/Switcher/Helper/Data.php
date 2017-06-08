<?php

/**
 * Module helper
 *
 * @category    QM
 * @package        QM_Switcher
 */
class QM_Switcher_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * config path to enabled flag
     */
    const XML_ENABLED_PATH = 'qm_switcher/settings/enabled';
    /**
     * default configuration attribute code
     */
    const DEFAULT_CONFIGURATION_ATTRIBUTE_CODE = 'default_configuration_id';
    /**
     * config path to autoselect first option if none specified
     */
    const XML_AUTOSELECT_FIRST_PATH = 'qm_switcher/settings/autoselect_first';
    /**
     * show out of stock combinations
     */
    const XML_SHOW_OUT_OF_STOCK_PATH = 'qm_switcher/settings/out_of_stock';
    /**
     * config path to allow out of stock products to be selected
     */
    const XML_NO_STOCK_SELECT_PATH = 'qm_switcher/settings/allow_no_stock_select';

    /**
     * check if the module is enabled
     * @access public
     * @return bool
     */
    public function isEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_ENABLED_PATH);
    }

    /**
     * check if module is enabled in admin
     * @return bool
     */
    public function isEnabledAdmin()
    {
        $store = Mage::app()->getRequest()->getParam('store', 0);
        return Mage::getStoreConfigFlag(self::XML_ENABLED_PATH, $store);
    }

    /**
     * get the default configuration
     * @access public
     * @return array|null
     */
    public function getDefaultValues($product)
    {
        if ($product->getTypeId() != 'configurable') {
            return null;
        }
        $product->load($product->getId());
        $defaultId = $product->getData(QM_Switcher_Helper_Data::DEFAULT_CONFIGURATION_ATTRIBUTE_CODE);
        if (!$defaultId) {
            //set first as default
            if (Mage::getStoreConfigFlag(self::XML_AUTOSELECT_FIRST_PATH)) {
                $simpleProducts = $this->getAllowProducts($product);
                $default        = false;
                foreach ($simpleProducts as $simple) {
                    /** @var Mage_Catalog_Model_Product $product */
                    if ($simple->getIsSalable() || $this->getAllowNoStockSelect()) {
                        //$default = $product;
                        $defaultId = $simple->getId();
                        break;
                    }
                }
            }
        }

        if (!$defaultId) {
            return null;
        }
        $defaultValues = array();
        foreach ($this->getAllowAttributes($product) as $attribute) {
            /** @var Mage_Catalog_Model_Product_Type_Configurable_Attribute $attribute */
            /** @var Mage_Catalog_Model_Resource_Eav_Attribute $productAttribute */
            $productAttribute                          = $attribute->getProductAttribute();
            $defaultValues[$productAttribute->getId()] = Mage::getResourceModel('catalog/product')
                ->getAttributeRawValue(
                    $defaultId,
                    $productAttribute->getAttributeCode(),
                    Mage::app()->getStore()->getId()
                );
        }
        return $defaultValues;
    }

    /**
     * Get Allowed Products
     *
     * @return array
     */
    public function getAllowProducts($product)
    {
        $products          = array();
        $skipSaleableCheck = Mage::helper('catalog/product')->getSkipSaleableCheck();
        $allProducts       = $product->getTypeInstance(true)
            ->getUsedProducts(null, $product);
        foreach ($allProducts as $product) {
            if ($product->isSaleable() || $skipSaleableCheck) {
                $products[] = $product;
            }
        }
        return $products;
    }

    /**
     * @return bool
     */
    public function getAllowNoStockSelect()
    {
        return Mage::getStoreConfigFlag(self::XML_SHOW_OUT_OF_STOCK_PATH) &&
        Mage::getStoreConfigFlag(self::XML_NO_STOCK_SELECT_PATH);
    }

    /**
     * Get allowed attributes
     *
     * @return array
     */
    public function getAllowAttributes($product)
    {
        return $product->getTypeInstance(true)
            ->getConfigurableAttributes($product);
    }

}
