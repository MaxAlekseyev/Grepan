<?php

class QM_AdvancedFeedback_Block_Adminhtml_Callback_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'qmadvfeedback';
        $this->_controller = 'adminhtml_callback';
        $this->_updateButton('save', 'label', Mage::helper('qmadvfeedback')->__('Save Callback'));
        $this->_updateButton('delete', 'label', Mage::helper('qmadvfeedback')->__('Delete Callback'));
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
        if( Mage::registry('current_callback') && Mage::registry('current_callback')->getId() ) {
            return Mage::helper('qmadvfeedback')->__("Edit Callback '%s'", $this->escapeHtml(Mage::registry('current_callback')->getName()));
        }
        else {
            return Mage::helper('qmadvfeedback')->__('Add Callback');
        }
    }
}
