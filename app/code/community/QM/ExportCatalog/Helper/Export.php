<?php

class QM_ExportCatalog_Helper_Export extends Mage_Core_Helper_Abstract
{
    const CMS = 'Magento';
    const ROOT_CATEGORY_ID = 1;
    const LOCAL_DELIVERY_COST_FOR_ALL_PRODUCTS = 0;
    const VENDOR_OFFER_TYPE = 'vendor.model';
    const CPA = 0; //use 'Покупка на Маркете' for yandex

    protected $_helper = null;
    private static $_isRunning = false;

    public function __construct()
    {
        $this->_helper = Mage::helper('qm_exportcatalog');
    }

    private function _removeLastErrors()
    {
        if (!self::$_isRunning) {
            self::$_isRunning = true;

            $helper = $this->_helper;
            $lastLogFile = Mage::getBaseDir('log') .'/'. $helper::LAST_ERRORS_LOG_FILE_NAME;

            if (file_exists($lastLogFile)) {
                unlink($lastLogFile);
            }
        }
    }

    public function exportToYml(QM_ExportCatalog_Model_Config $config)
    {
        $this->_removeLastErrors();

        try {
            $content = $this->_generateContent($config);
        } catch (Exception $e) {
            $this->_helper->error($e->getMessage());
            return false;
        }

        $this->_saveContent($content, $config);
        return true;
    }

    protected function _getExportPath($config)
    {
        $exportDir = $this->_helper->getExportDir();

        if (!is_dir($exportDir)) {
            mkDir($exportDir);
        }

        return $exportDir . '/' . $config->getFileName();
    }

    protected function _saveContent($content, $config)
    {
        $fullPath = $this->_getExportPath($config);
        $tempPath = $fullPath . '.temp';

        file_put_contents($tempPath, $content);

        if (is_file($tempPath)) {
            rename($tempPath, $fullPath);
        }

    }

    protected function _generateContent($config)
    {
        $xml = new XMLWriter();

        $xml->openMemory();
        $xml->setIndentString('  ');
        $xml->startDocument('1.0', 'windows-1251');

        $this->_writeYmlCatalog($xml, $config);

        $xml->endDocument();

        return $xml->outputMemory();
    }

    protected function _writeYmlCatalog($xml, $config)
    {
        $xml->writeDTD('yml_catalog', null, 'shops.dtd');
        $xml->text("\n");
        $xml->setIndent(true);

        $xml->startElement('yml_catalog');
        $xml->writeAttribute('date', Mage::getModel('core/date')->date('Y-m-d H:i'));

        $this->_writeShop($xml, $config);

        $xml->endElement();
    }

    protected function _writeShop($xml, $config)
    {
        $xml->startElement('shop');

        $this->_writeElementText($xml, 'name',Mage::getStoreConfig('qm_exportcatalog/qm_exportcatalog_settings/store_name'),true);
        $this->_writeElementText($xml, 'company',Mage::getStoreConfig('qm_exportcatalog/qm_exportcatalog_settings/company_name'),true);
        $this->_writeElementText($xml, 'url',Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB),true);
        $this->_writeElementText($xml, 'platform',self::CMS,true);
        $this->_writeElementText($xml, 'version',Mage::getVersion(),true);
        $this->_writeElementText($xml, 'agency',Mage::getStoreConfig('qm_exportcatalog/qm_exportcatalog_settings/agency_name'),true);
        $this->_writeElementText($xml, 'email',Mage::getStoreConfig('trans_email/ident_general/email'),true);

        $this->_writeCurrencies($xml);
        $this->_writeCategories($xml, $config);
        $this->_writeLocalDeliveryCostForAllProducts($xml);
        $this->_writeOffers($xml, $config);

        $xml->endElement();
    }

    protected function _writeCurrencies($xml)
    {
        $xml->startElement('currencies');
        $xml->startElement('currency');

        $xml->writeAttribute('id', Mage::app()->getStore()->getCurrentCurrencyCode());
        $xml->writeAttribute('rate', '1');

        $xml->endElement();
        $xml->endElement();
    }

    protected function _writeCategories($xml, $config)
    {
        $xml->startElement('categories');

        $categoryIds = $this->_addParentCategories($config->getExportCatalogsArray());

        if ($categoryIds && is_array($categoryIds)) {

            if (!count($categoryIds)) {
                throw new Exception($this->_helper->__('Zero categories was written - yml not created'));
            }

            foreach ($categoryIds as $categoryId) {
                $xml->startElement('category');

                $category = Mage::getModel('catalog/category')->load($categoryId);

                $xml->writeAttribute('id', $categoryId);

                if ($category->getParentId() != self::ROOT_CATEGORY_ID) {
                    $xml->writeAttribute('parentId', $category->getParentId());
                }

                $xml->text($category->getName());
                $xml->endElement();
            }
        }

        $xml->endElement();
    }

    protected function _writeLocalDeliveryCostForAllProducts($xml)
    {
        $xml->startElement('local_delivery_cost');
        $xml->text(self::LOCAL_DELIVERY_COST_FOR_ALL_PRODUCTS);
        $xml->endElement();
    }

    protected function _writeOffers($xml, $config)
    {
        $xml->startElement('offers');

        $dataArray = $this->_getExportDataArray($config);

        $totalOffers = 0;

        foreach ($dataArray as $data) {
            try {
                $offerXml = new XMLWriter();
                $offerXml->openMemory();

                $this->_writeSingleOffer($offerXml, $config, $data->getProduct(), $data->getCategory());

                $xml->writeRaw($offerXml->outputMemory());

                $totalOffers++;
            } catch (Exception $e) {
                $this->_helper->errorForProduct($e->getMessage(), $data->getProduct());
            }
        }

        if (!$totalOffers) {
            throw new Exception($this->_helper->__('Zero offers was written - yml not created'));
        }

        $xml->endElement();
    }

    protected function _writeSingleOffer($xml, $config, Mage_Catalog_Model_Product $product, Mage_Catalog_Model_Category $category)
    {
        $helper = Mage::helper('qm_exportcatalog');

        $offerType = $helper->getOfferType();

        if ($offerType == $helper::OFFER_TYPE_SIMPLE) {
            $this->_writeSimpleSingleOffer($xml, $config, $product, $category);
            return;
        }

        if ($offerType == $helper::OFFER_TYPE_VENDOR_MODEL) {
            $this->_writeVendorSingleOffer($xml, $config, $product, $category);
            return;
        }

        if ($offerType == $helper::OFFER_TYPE_SMART) {

            $message = '';

            $offerXml = new XMLWriter();
            $offerXml->openMemory();

            try {
                $this->_writeVendorSingleOffer($offerXml, $config, $product, $category);

                $xml->writeRaw($offerXml->outputMemory());
                return;
            } catch (Exception $e) {
                $message .= 'For vendor.model :' . $e->getMessage();
            }

            $offerXml = new XMLWriter();
            $offerXml->openMemory();

            try {
                $this->_writeSimpleSingleOffer($xml, $config, $product, $category);

                $xml->writeRaw($offerXml->outputMemory());
                return;
            } catch (Exception $e) {
                $message .= $this->_helper->__('For simple model :') . $e->getMessage();
                throw new Exception($message);
            }

            return;
        }
    }

    protected function _writeVendorSingleOffer($xml, $config, Mage_Catalog_Model_Product $product, Mage_Catalog_Model_Category $category)
    {
        $xml->startElement('offer');

        $xml->writeAttribute('id', $this->_getOfferId($product, $category));
        $xml->writeAttribute('type', self::VENDOR_OFFER_TYPE);
        $xml->writeAttribute('available', $product->isInStock() ? 'true' : 'false');

        $this->_writeBidAttribute($xml, $config);
        $this->_writeCbidAttribute($xml, $config);

        $this->_writeElementText($xml, 'url', $product->getProductUrl(), false);
        $this->_writeElementText($xml, 'price', $product->getPrice(), true);
        $this->_writeElementText($xml, 'currencyId', Mage::app()->getStore()->getCurrentCurrencyCode(), true);
        $this->_writeElementText($xml, 'categoryId', $category->getId(), true);

        $this->_writePictureElement($xml, $product);

        $this->_writeElementText($xml, 'typePrefix', $category->getName(), false);
        $this->_writeElementText($xml, 'vendor', $this->_helper->getProductVendor($product), true);
        $this->_writeElementText($xml, 'model', $this->_helper->getProductModel($product), true);

        $this->_writeDescriptionElement($xml, $product);

        $this->_writeElementText($xml, 'sales_notes', $this->_helper->getProductSalesNotes($product), false);
        $this->_writeElementText($xml, 'country_of_origin', $product->getAttributeText('country_of_manufacture'), false);
        $this->_writeElementText($xml, 'cpa', self::CPA, false);

        if ($this->_helper->isAddRelated()) {
            $this->_writeElementText($xml, 'rec', $this->_getRecomendedProducts($product, $config), false);
        }

        $xml->endElement();
    }

    protected function _writeSimpleSingleOffer($xml, $config, Mage_Catalog_Model_Product $product, Mage_Catalog_Model_Category $category)
    {
        $xml->startElement('offer');

        $xml->writeAttribute('id', $this->_getOfferId($product, $category));
        $xml->writeAttribute('available', $product->isInStock() ? 'true' : 'false');

        $this->_writeBidAttribute($xml, $config);
        $this->_writeCbidAttribute($xml, $config);

        $this->_writeElementText($xml, 'url', $product->getProductUrl(), false);
        $this->_writeElementText($xml, 'price', $product->getPrice(), true);
        $this->_writeElementText($xml, 'currencyId', Mage::app()->getStore()->getCurrentCurrencyCode(), true);
        $this->_writeElementText($xml, 'categoryId', $category->getId(), true);

        $this->_writePictureElement($xml, $product);

        $this->_writeElementText($xml, 'name', $product->getName(), true);
        $this->_writeElementText($xml, 'vendor', $this->_helper->getProductVendor($product), false);

        $this->_writeDescriptionElement($xml, $product);

        $this->_writeElementText($xml, 'sales_notes', $this->_helper->getProductSalesNotes($product), false);
        $this->_writeElementText($xml, 'country_of_origin', $product->getAttributeText('country_of_manufacture'), false);
        $this->_writeElementText($xml, 'cpa', self::CPA, false);

        $xml->endElement();
    }

    protected function _writeBidAttribute($xml, $config)
    {
        if($config->getBid()) {
            $xml->writeAttribute('bid',$config->getBid());
        }
    }

    protected function _writeCbidAttribute($xml, $config)
    {
        if($config->getCbid()) {
            $xml->writeAttribute('cbid',$config->getCbid());
        }
    }


    protected function _getOfferId(Mage_Catalog_Model_Product $product, Mage_Catalog_Model_Category $category)
    {
        return 'p' . $product->getId() . 'c' . $category->getId();
    }

    private function _writePictureElement($xml, Mage_Catalog_Model_Product $product)
    {

        $productImage = null;
        try {
            $productImage = Mage::helper('catalog/image')->init($product, 'image');
        } catch (Exception $e) {

        }

        if ($productImage && trim($productImage)) {
            $xml->startElement('picture');
            $xml->text($productImage);
            $xml->endElement();
        }
    }

    private function _writeDescriptionElement($xml, Mage_Catalog_Model_Product $product)
    {
        if ($product->getDescription() && trim($product->getDescription())) {
            $xml->startElement('description');
            $xml->writeCData($product->getDescription());
            $xml->endElement();
        }
    }

    private function _writeElementText($xml, $elementName, $elementText, $requiredElement)
    {
        if ((!$elementText || !trim($elementText)) && $requiredElement) {
            throw new Exception($this->_helper->__('Empty nested field ' . $elementName));
        }

        if (trim($elementText)) {
            $xml->startElement($elementName);
            $xml->text($elementText);
            $xml->endElement();
        }

    }

    private function _addParentCategories($categoryIds)
    {
        $categoryIdsWithParents = $categoryIds;

        foreach ($categoryIdsWithParents as $id) {
            if ($id == self::ROOT_CATEGORY_ID) {
                continue;
            }

            $parent = Mage::getModel('catalog/category')->load($id);

            do {
                $parent = Mage::getModel('catalog/category')->load($parent->getParentId());

                $isAllParentsAddeded = $parent->getId() == self::ROOT_CATEGORY_ID;

                if (!$isAllParentsAddeded) {
                    if (!in_array($parent->getId(), $categoryIdsWithParents, true)) {
                        array_push($categoryIdsWithParents, $parent->getId());
                    }
                } else {
                    break;
                }

            } while (true);
        }

        return $categoryIdsWithParents;
    }

    /**
     * Get string with comma of related product ids that we can export
     *
     * @param Mage_Catalog_Model_Product $product
     * @param $config
     * @return string
     */
    private function _getRecomendedProducts(Mage_Catalog_Model_Product $product, $config)
    {
        $recomendedProductsIds = array();

        $exportDataArray = $this->_getExportDataArray($config);

        $relatedProductsIds = $product->getRelatedProductIds();

        foreach ($relatedProductsIds as $relatedProductId) {

            foreach ($exportDataArray as $data) {

                if ($relatedProductId === $data->getProduct()->getId()) {
                    array_push($recomendedProductsIds, $this->_getOfferId($data->getProduct(), $data->getCategory()));
                }
            }
        }

        return implode(",", $recomendedProductsIds);
    }

    protected function _addSelectToCollection(Mage_Catalog_Model_Resource_Product_Collection $collection)
    {
        $collection->addAttributeToSelect('price')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('product_url')
            ->addAttributeToSelect('country_of_manufacture')
            ->addAttributeToSelect('description')
            ->addAttributeToSelect('related_product_ids')
            ->addAttributeToSelect($this->_helper->getProductModelAttrCode())
            ->addAttributeToSelect($this->_helper->getProductVendorAttrCode());
    }

    /**
     * @param $config
     * @return array(Varien_Object(product,category))
     */
    private function _getExportDataArray($config)
    {
        $exportData = array();

        $categoryIds = $config->getExportCatalogsArray();

        foreach ($categoryIds as $categoryId) {
            $category = Mage::getModel('catalog/category')->load($categoryId);

            $products = $category->getProductCollection()->addAttributeToFilter('type_id', 'simple');

            $this->_addSelectToCollection($products);

            foreach ($products as $product) {
                //$product = $product->load($product->getId());
                $product->setStoreId(Mage::app()->getDefaultStoreView()->getId());

                if ($config->isProductValid($product)) {

                    $data = new Varien_Object();
                    $data->setProduct($product);
                    $data->setCategory($category);

                    array_push($exportData, $data);
                }
            }
        }

        return $exportData;
    }

}