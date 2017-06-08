<?php

class QM_AdvancedFeedback_Block_Adminhtml_Callback_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('callback_');
        $form->setFieldNameSuffix('callback');
        $this->setForm($form);
        $fieldset = $form->addFieldset('callback_form', array('legend'=>Mage::helper('qmadvfeedback')->__('Callback')));

        $fieldset->addField('name', 'text', array(
            'label' => Mage::helper('qmadvfeedback')->__('Name'),
            'name'  => 'name',
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

        $isSpam = (int)Mage::registry('current_callback')->getSpam();
        $fieldset->addField('spam', 'checkbox', array(
            'label'              => Mage::helper('qmadvfeedback')->__('Spam'),
            'name'               => 'spam',
            'checked'            => $isSpam,
            'after_element_html' => '<input style="display:none;" id="callback_spam_dupl" name="callback[spam]" value="' . !$isSpam . '"' . (!$isSpam ? 'checked="checked"' : '') . ' type="checkbox">',
            'onclick'            => <<<DATA
(function(self) {
    self.value = self.checked ? 1 : 0;
    var checkD = document.getElementById('callback_spam_dupl');
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
    (function(){var inp=$("callback_page_url");if(inp.value){var lnk='<a href="'+inp.value+'" target="_blank">'+inp.value+'</a>';inp.insert({after:lnk}).hide()}else{inp.up("tr").hide()}})()
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
                'name'  => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
            Mage::registry('current_callback')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $formValues = Mage::registry('current_callback')->getDefaultValues();
        if (!is_array($formValues)){
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getCallbackData()){
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getCallbackData());
            Mage::getSingleton('adminhtml/session')->setCallbackData(null);
        }
        elseif (Mage::registry('current_callback')){
            $formValues = array_merge($formValues, Mage::registry('current_callback')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
