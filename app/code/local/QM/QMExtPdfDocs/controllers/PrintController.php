<?php

class QM_QMExtPdfDocs_PrintController extends Mage_Core_Controller_Front_Action
{

    public function printInvoiceAction()
    {

        if ($invoiceId = $this->getRequest()->getParam('invoice_id')) {
            if ($invoice = Mage::getModel('sales/order_invoice')->load($invoiceId)) {
                $pdf = Mage::getModel('sales/order_pdf_invoice')->getPdf(array($invoice));
                $this->_prepareDownloadResponse('invoice'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').
                    '.pdf', $pdf->render(), 'application/pdf');
            }
        }
        else {
            $this->_forward('noRoute');
        }
    }
    public function printShipmentAction()
    {
        if ($shipmentId = $this->getRequest()->getParam('shipment_id')) {
            if ($shipment = Mage::getModel('sales/order_shipment')->load($shipmentId)) {
                $pdf = Mage::getModel('sales/order_pdf_shipment')->getPdf(array($shipment));
                $this->_prepareDownloadResponse('shipment'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').
                    '.pdf', $pdf->render(), 'application/pdf');

            }
        }
        else {
            $this->_forward('noRoute');
        }
    }
}