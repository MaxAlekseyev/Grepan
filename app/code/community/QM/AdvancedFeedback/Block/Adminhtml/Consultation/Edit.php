<?php

class QM_AdvancedFeedback_Block_Adminhtml_Consultation_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'qmadvfeedback';
        $this->_controller = 'adminhtml_consultation';
        $this->_updateButton('save', 'label', Mage::helper('qmadvfeedback')->__('Save Сonsultation'));
        $this->_updateButton('delete', 'label', Mage::helper('qmadvfeedback')->__('Delete Сonsultation'));
        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('qmadvfeedback')->__('Save And Continue Edit'),
            'onclick'    => 'saveAndContinueEdit()',
            'class'        => 'save',
        ), -100);
        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('current_consultation') && Mage::registry('current_consultation')->getId() ) {
            return Mage::helper('qmadvfeedback')->__("Edit Сonsultation '%s'", $this->escapeHtml(Mage::registry('current_consultation')->getName()));
        }
        else {
            return Mage::helper('qmadvfeedback')->__('Add Сonsultation');
        }
    }
}
