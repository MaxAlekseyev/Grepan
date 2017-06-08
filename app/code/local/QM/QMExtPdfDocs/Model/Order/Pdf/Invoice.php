<?php

/**
 * Inchoo PDF rewrite for custom attribute
 * * Attribute "inchoo_warehouse_location" has to be set manually
 * Original: Sales Order Invoice PDF model
 *
 * @category   Inchoo
 * @package    Inhoo_Invoice
 * @author     Mladen Lotar - Inchoo <mladen.lotar@inchoo.net>
 */
class QM_QMExtPdfDocs_Model_Order_Pdf_Invoice extends Mage_Sales_Model_Order_Pdf_Invoice
{
    public function getPdf($invoices = array())
    {
        $this->_beforeGetPdf();
        $this->_initRenderer('invoice');

        $pdf = new Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

        foreach ($invoices as $invoice) {
            if ($invoice->getStoreId()) {
                Mage::app()->getLocale()->emulate($invoice->getStoreId());
            }
            $page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
            $pdf->pages[] = $page;

            $order = $invoice->getOrder();

            /* Add image */
            $this->insertLogo($page, $invoice->getStore());

            $height = 815;
            $this->_setFontRegular($page, 16);
            $page->setFillColor(new Zend_Pdf_Color_GrayScale(0.8));
            $page->drawRectangle(25, $height, 570, $height - 30);
            $page->setFillColor(new Zend_Pdf_Color_GrayScale(0.1));

            /*add Shipment №*/

            $page->drawText(Mage::helper('sales')->__('Рахунок № ') . $invoice->getIncrementId(), 35, 795, 'UTF-8');  //рисует номер счета
            $page->drawText(Mage::helper('sales')->__('від : ') . Mage::helper('core')->__($this->getCreatedDate($order)), 200, 795, 'UTF-8');                                                          // рисует дату заказ
            $page->setFillColor(new Zend_Pdf_Color_GrayScale(0.8));
            $page->drawRectangle(25, $height - 30, 570, $height - 130);
            $page->setFillColor(new Zend_Pdf_Color_GrayScale(0.1));

            /*add Producer details */

            $details = Mage::helper('qmExtPdfDocs')->getInvoiceDetails();
            $height = 773;
            $this->_setFontRegular($page, 10);
            $page->drawText(Mage::helper('qmExtPdfDocs')->__('Постачальник :'), 35, 773, 'UTF-8');
            foreach ($details as $detailString) {
                $page->drawText(Mage::helper('qmExtPdfDocs')->__($detailString), 200, $height, 'UTF-8');
                $height -= 10;
            }
            $height -= 18;
            $page->setFillColor(new Zend_Pdf_Color_GrayScale(0.8));
            $page->drawRectangle(25, $this->y - 130, 570, $height - 30);  //  рисует заливочную рамку покупателя
            $page->setFillColor(new Zend_Pdf_Color_GrayScale(0.1));

            /*add Customer details №*/

            $page->drawText(Mage::helper('qmExtPdfDocs')->__('Платник :'), 35, $height, 'UTF-8');
            foreach ($this->getCustomerDetails($order) as $key => $detail) {
                if ($key == 'name') $key = "ім'я";
                if ($key == 'email') $key = "електронна пошта";
                if ($key == 'telephone') $key = "телефон";
                $page->drawText(Mage::helper('qmExtPdfDocs')->__($key), 100, $height, 'UTF-8');
                $page->drawText(Mage::helper('qmExtPdfDocs')->__($detail), 200, $height, 'UTF-8');
                $height -= 10;
            }

            /*add Sales conditions №*/

            $page->setFillColor(new Zend_Pdf_Color_GrayScale(0.8));
            $page->drawRectangle(25, $height, 570, $height - 12);
            $page->setFillColor(new Zend_Pdf_Color_GrayScale(0.1));
            $page->drawText(Mage::helper('qmExtPdfDocs')->__('Умови продажу :'), 35, $height - 8, 'UTF-8');
            $salesTerms = (Mage::helper('qmExtPdfDocs')->getSalesTerms());
            $page->drawText(Mage::helper('qmExtPdfDocs')->__($salesTerms), 200, $height - 8, 'UTF-8');

            /* Add table */
            $page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
            $page->setLineWidth(0.5);
            $this->y -= 190;
            $height -= 20;

            $page->setFillColor(new Zend_Pdf_Color_GrayScale(0.85));
            $page->drawRectangle(25, $height, 570, $height - 15);
            $page->setFillColor(new Zend_Pdf_Color_GrayScale(0.1));
            $this->y -= 10;
            $height -= 10;

            /* Add table head */

            $page->drawText(Mage::helper('sales')->__('Назва'), 35, $height, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('Кількість'), 325, $height, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('Ціна'), 370, $height, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('Сума'), 450, $height, 'UTF-8');

            $height -= 21;

            $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));

            $this->y = $height;

            /* Add body */

            foreach ($invoice->getAllItems() as $item) {
                if ($item->getOrderItem()->getParentItem()) {
                    continue;
                }

                if ($this->y < 15) {
                    $page = $this->newPage(array('table_header' => true));

                }

                /* Draw item */

                $page = $this->_drawItem($item, $page, $order);
                $page->drawLine(35, $this->y + 14, 570, $this->y + 14);
                /*$this->y-= 20;*/
            }
            $this->y -= 15;

            /* Add totals */

            $orderTotalsrtinf = $this->getInvoiceTotal($invoice);
            $totlhead = 'Всьго на сумму: ';
            $page->drawText(Mage::helper('sales')->__('  ' . $totlhead . $orderTotalsrtinf), 35, $this->y, 'UTF-8');
            $page->drawLine(110, $this->y - 5, 570, $this->y - 5);

            $this->y -= 35;
            $page->drawText(Mage::helper('sales')->__('Постачальник'), 35, $this->y, 'UTF-8');
            $page->drawLine(110, $this->y - 5, 200, $this->y - 5);

            if ($invoice->getStoreId()) {
                Mage::app()->getLocale()->revert();
            }

        }
        $this->_afterGetPdf();

        return $pdf;
    }

    public function newPage(array $settings = array())
    {
        /* Add new table head */
        $page = $this->_getPdf()->newPage(Zend_Pdf_Page::SIZE_A4);
        $this->_getPdf()->pages[] = $page;
        $this->y = 800;

        if (!empty($settings['table_header'])) {
            $this->_setFontRegular($page);
            $page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
            $page->setLineWidth(0.5);

            $page->setFillColor(new Zend_Pdf_Color_GrayScale(0.85));
            $page->drawRectangle(25, $this->y, 570, $this->y - 15);
            $page->setFillColor(new Zend_Pdf_Color_GrayScale(0.1));
            $this->y -= 10;

            /* Add table head */
            $page->drawText(Mage::helper('sales')->__('Назва'), 35, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('Кількість'), 325, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('Ціна'), 370, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('Сума'), 450, $this->y, 'UTF-8');

            $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
            $this->y -= 20;
        }
        return $page;
    }

    public function getCustomerDetails($order)
    {
        $details = array();
        $customer = $order->getCustomerFirstname() . ' ' . $order->getCustomerLastname();
        $details['name'] = $customer;
        $details['email'] = $order->getCustomerEmail();
        $details['telephone'] = $order->getShippingAddress()->getTelephone();
        return $details;
    }

    public function getInvoiceTotal($invoice)
    {
        $grandTotal = $invoice->getGrandTotal();
        $grandTotalstring = Mage::helper('qmExtPdfDocs/sumProp')->num2str($grandTotal);

        return $grandTotalstring;
    }

    public function getCreatedDate($order)
    {
        $orderDate = $order->getCreatedAt();
        $orderDate = substr("$orderDate", 0, 10);
        return $orderDate;
    }

}