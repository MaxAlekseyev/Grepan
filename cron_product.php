<?php
require_once 'app/Mage.php';

if (!Mage::isInstalled()) {
    echo "Application is not installed yet, please complete install wizard first.";
    exit;
}

umask(0);
Mage::setIsDeveloperMode(true);
Mage::app(0);

set_time_limit(0);
ini_set('display_errors', 1);

class Exporter {
    const ENCLOSURE = '"';
    const DELIMITER = ',';
    public function export()
    {
        $fileName = 'products'.date("YmdHis").'csv';

        //$store_id = Mage::app()->getStore()->getId();
	$store_id = 0;
        //$collection = Mage::getModel('catalog/product')->setStoreId($store_id)->getCollection()->addAttributeToSelect('*');

        $collection = Mage::getResourceModel('catalog/product_collection')
            ->addStoreFilter($store_id)
            ->addAttributeToSelect('*')
        ;

        $fp = fopen($fileName, 'w');

        foreach($collection as $_product){

            //echo sprintf('%s <br>',$_product->getProductUrl());
	    $record = array($_product->getName(), $_product->getProductUrl());
            fputcsv($fp, $record, self::DELIMITER, self::ENCLOSURE);
        }

        fclose($fp);

        return $fileName;
    }
}

$export = new Exporter();
$test = $export->export();





	






