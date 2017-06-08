<?php

class QM_OneclickOrder_Block_Adminhtml_Order_Edit_Tab_Stores extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setFieldNameSuffix('order');
        $this->setForm($form);
        $fieldset = $form->addFieldset('order_stores_form', array('legend'=>Mage::helper('qmoneclickorder')->__('Store views')));
        $field = $fieldset->addField('store_id', 'multiselect', array(
            'name'  => 'stores[]',
            'label' => Mage::helper('qmoneclickorder')->__('Store Views'),
            'title' => Mage::helper('qmoneclickorder')->__('Store Views'),
            'required'  => true,
            'values'=> Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
        ));
        $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
        $field->setRenderer($renderer);
          $form->addValues(Mage::registry('current_oneclickorder')->getData());
        return parent::_prepareForm();
    }
}
