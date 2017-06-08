<?php

class QM_OneclickOrder_Block_Adminhtml_Order_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('order_');
        $form->setFieldNameSuffix('order');
        $this->setForm($form);
        $fieldset = $form->addFieldset('order_form', array('legend'=>Mage::helper('qmoneclickorder')->__('Order')));

        $fieldset->addField('name', 'text', array(
            'label' => Mage::helper('qmoneclickorder')->__('Name'),
            'name'  => 'name',
        ));
        $fieldset->addField('telephone', 'text', array(
            'label' => Mage::helper('qmoneclickorder')->__('Telephone'),
            'name'  => 'telephone',
        ));
        $fieldset->addField('email', 'text', array(
            'label' => Mage::helper('qmoneclickorder')->__('Email'),
            'name'  => 'email',
        ));
        $fieldset->addField('comment', 'textarea', array(
            'label' => Mage::helper('qmoneclickorder')->__('Comment'),
            'name'  => 'comment',
            'style' => 'height:5em'
        ));
        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('qmoneclickorder')->__('Status'),
            'name'  => 'status',
            'values'=> Mage::getModel('qmoneclickorder/config_source_status')->toOptionArray()
        ));

        if (Mage::app()->isSingleStoreMode()){
            $fieldset->addField('store_id', 'hidden', array(
                'name'  => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
            Mage::registry('current_oneclickorder')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $formValues = Mage::registry('current_oneclickorder')->getDefaultValues();
        if (!is_array($formValues)){
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getOrderData()){
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getOrderData());
            Mage::getSingleton('adminhtml/session')->setOrderData(null);
        }
        elseif (Mage::registry('current_oneclickorder')){
            $formValues = array_merge($formValues, Mage::registry('current_oneclickorder')->getData());
        }

        $values = new Varien_Object($formValues);
        if ($values->getProductSku()) {
            $values->setProductPrice(Mage::helper('core')->currency($values->getProductPrice(), true, false));
            $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $values->getProductSku());

            $html = <<<HTML
<table class="oneclickorder-table">
    <tr>
        <td>{$this->__('Sku')}:</td>
        <td>{$values->getProductSku()}</td>
    </tr>
    <tr>
        <td>{$this->__('Name')}:</td>
        <td><a href="{$product->getProductUrl()}" target="_blank">{$values->getProductName()}</a></td>
    </tr>
    <tr>
        <td>{$this->__('Price')}:</td>
        <td>{$values->getProductPrice()}</td>
    </tr>
</table>
HTML;
            $style = <<<STYLE
<style>
.oneclickorder-table tr td:first-child{
    font-weight: bold;
    color: #555;
    width: 50px;
    padding-right: 5px;
}
</style>
STYLE;
            $fieldset->addField('product_html', 'note', array(
                'label' => Mage::helper('qmoneclickorder')->__('Product'),
                'text' => $html,
                'after_element_html' => $style
            ));
        } else {
            $fieldset->addField('product_wrong', 'note', array(
                'label' => Mage::helper('qmoneclickorder')->__('Product'),
                'text'  => '<span style="color:#ea7601;">' . $this->__('Product is not defined. Something went wrong.') . '</span>'
            ));
        }

        $form->setValues($values->getData());
        return parent::_prepareForm();
    }
}
