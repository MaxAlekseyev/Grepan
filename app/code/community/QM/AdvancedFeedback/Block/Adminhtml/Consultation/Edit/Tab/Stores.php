<?php

class QM_AdvancedFeedback_Block_Adminhtml_Consultation_Edit_Tab_Stores extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setFieldNameSuffix('consultation');
        $this->setForm($form);
        $fieldset = $form->addFieldset('consultation_stores_form', array('legend'=>Mage::helper('qmadvfeedback')->__('Store views')));
        $field = $fieldset->addField('store_id', 'multiselect', array(
            'name'  => 'stores[]',
            'label' => Mage::helper('qmadvfeedback')->__('Store Views'),
            'title' => Mage::helper('qmadvfeedback')->__('Store Views'),
            'required'  => true,
            'values'=> Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
        ));
        $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
        $field->setRenderer($renderer);
          $form->addValues(Mage::registry('current_consultation')->getData());
        return parent::_prepareForm();
    }
}
