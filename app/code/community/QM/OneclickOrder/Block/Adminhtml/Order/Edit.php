<?php

class QM_OneclickOrder_Block_Adminhtml_Order_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'qmoneclickorder';
        $this->_controller = 'adminhtml_order';
        $this->_updateButton('save', 'label', Mage::helper('qmoneclickorder')->__('Save Order'));
        $this->_updateButton('delete', 'label', Mage::helper('qmoneclickorder')->__('Delete Order'));
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('qmoneclickorder')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('current_oneclickorder') && Mage::registry('current_oneclickorder')->getId() ) {
            return Mage::helper('qmoneclickorder')->__("Edit Order #%s", $this->escapeHtml(Mage::registry('current_oneclickorder')->getId()));
        }
        else {
            return Mage::helper('qmoneclickorder')->__('Add Order');
        }
    }
}
