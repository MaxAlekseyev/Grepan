<?php

class QM_NewFromTo_Model_Form_Observer
{
    public function prepareForm(Varien_Event_Observer $observer)
    {
        $form        = $observer->getForm();
        $product     = $form->getDataObject();
        $elementFrom = $form->getElement('news_from_date');
        $elementTo   = $form->getElement('news_to_date');
        $helper = Mage::helper('qmnewfromto');

        if ($helper->isEnable() && $this->_isNewProduct($product)
            && $elementFrom instanceof Varien_Data_Form_Element_Date
            && $elementTo instanceof Varien_Data_Form_Element_Date) {

            $fromDate = $helper->getFromDate();
            if ($fromDate) {
                $elementFrom->setValue($fromDate);
            }

            $toDate = $helper->getToDate();
            if ($toDate) {
                $elementTo->setValue($toDate);
            }

        }

    }

    protected function _isNewProduct($product)
    {
        return $product->getId() == null;
    }
}