<?php

class QM_AdvancedFeedback_Block_Adminhtml_Consultation_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('consultation_');
        $form->setFieldNameSuffix('consultation');
        $this->setForm($form);
        $fieldset = $form->addFieldset('consultation_form', array('legend'=>Mage::helper('qmadvfeedback')->__('Сonsultation')));

        $fieldset->addField('name', 'text', array(
            'label' => Mage::helper('qmadvfeedback')->__('Name'),
            'name'  => 'name',
        ));

        $fieldset->addField('email', 'text', array(
            'label' => Mage::helper('qmadvfeedback')->__('Email'),
            'name'  => 'email',
        ));

        $fieldset->addField('telephone', 'text', array(
            'label' => Mage::helper('qmadvfeedback')->__('Telephone'),
            'name'  => 'telephone',
        ));

        $fieldset->addField('preferred_time', 'text', array(
            'label' => Mage::helper('qmadvfeedback')->__('Preferred Time'),
            'name'  => 'preferred_time',
        ));

        $fieldset->addField('comment', 'textarea', array(
            'label' => Mage::helper('qmadvfeedback')->__('Comment'),
            'name'  => 'comment',
        ));

        $isSpam = (int)Mage::registry('current_consultation')->getSpam();
        $fieldset->addField('spam', 'checkbox', array(
            'label'              => Mage::helper('qmadvfeedback')->__('Spam'),
            'name'               => 'spam',
            'checked'            => $isSpam,
            'after_element_html' => '<input style="display:none;" id="consultation_spam_dupl" name="consultation[spam]" value="' . !$isSpam . '"' . (!$isSpam ? 'checked="checked"' : '') . ' type="checkbox">',
            'onclick'            => <<<DATA
(function(self) {
    self.value = self.checked ? 1 : 0;
    var checkD = document.getElementById('consultation_spam_dupl');
    checkD.checked = !self.checked;
    checkD.value = self.value;
})(this)
DATA
        ));

        $fieldset->addField('page_url', 'text', array(
            'label' => Mage::helper('qmadvfeedback')->__('Page Url'),
            'name'  => 'page_url',
            'after_element_html' => <<<DATA
<script type="text/javascript">
    (function(){var inp=$("consultation_page_url");if(inp.value){var lnk='<a href="'+inp.value+'" target="_blank">'+inp.value+'</a>';inp.insert({after:lnk}).hide()}else{inp.up("tr").hide()}})()
</script>
DATA
        ));
        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('qmadvfeedback')->__('Status'),
            'name'  => 'status',
            'values'=> Mage::getModel('qmadvfeedback/config_source_status')->toOptionArray()
        ));
        if (Mage::app()->isSingleStoreMode()){
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            Mage::registry('current_consultation')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $formValues = Mage::registry('current_consultation')->getDefaultValues();
        if (!is_array($formValues)){
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getConsultationData()){
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getConsultationData());
            Mage::getSingleton('adminhtml/session')->setConsultationData(null);
        }
        elseif (Mage::registry('current_consultation')){
            $formValues = array_merge($formValues, Mage::registry('current_consultation')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
