<?php

class QM_QMExtPdfDocs_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getInvoiceDetails()
    {
        $details = Mage::getStoreConfig("sales_pdf/invoice/custom_pdf_options_details");
        $details = explode(PHP_EOL, $details);
        $i = 0;
        foreach ($details as $values) {
            $details[$i] = rtrim($values);
            $i++;
        }
        return $details;

    }

    public function getSalesTerms()
    {
        return Mage::getStoreConfig("sales_pdf/invoice/custom_pdf_options_sales_terms");

    }

    public function getPrintUrl($order, $type)
    {
        if ($type == 'invoice') {
            $order_id = $order->getId();
            $invoices = Mage::getResourceModel('sales/order_invoice_collection')
                ->setOrderFilter($order_id)
                ->load();
            $invoice_id = $invoices->getData()[0]["entity_id"];
            $url = Mage::getUrl('print/print/printInvoice', array('invoice_id' => $invoice_id));
            return $url;
        }
        if ($type == 'shipment') {
            $order_id = $order->getId();
            $shipment = Mage::getResourceModel('sales/order_shipment_collection')
                ->setOrderFilter($order_id)
                ->load();
            $shipment_id = $shipment->getData()[0]["entity_id"];
            $url = Mage::getUrl('print/print/printShipment', array('shipment_id' => $shipment_id));
            return $url;
        }
    }

}