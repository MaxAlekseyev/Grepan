<?php
class QM_LayeredNavigation_Block_Adminhtml_Page_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id'; 
        $this->_blockGroup = 'qm_layerednavigation';
        $this->_controller = 'adminhtml_page';
        
        $this->_addButton('save_and_continue', array(
                'label'     => Mage::helper('salesrule')->__('Save and Continue Edit'),
                'onclick'   => 'saveAndContinueEdit()',
                'class' => 'save'
            ), 10);
       $this->_formScripts[] = " function saveAndContinueEdit(){ editForm.submit($('edit_form').action + 'continue/edit') } ";        
       $this->_formScripts[] = " function showOptions(sel) {
            if (!sel.value)
                return;
            new Ajax.Request('" . $this->getUrl('*/*/options', array('isAjax'=>true)) ."', {
                parameters: {code : sel.value, name: sel.id},
                onSuccess: function(transport) {
                    $('option_' + sel.id.substring(sel.id.length-1) ).up().update(transport.responseText);
                }
            });
        }";         
    }

    public function getHeaderText()
    {
        $header = Mage::helper('qm_layerednavigation')->__('New Page');
        
        if (Mage::registry('qm_layerednavigation_page')->getPageId()) {
            if (Mage::registry('qm_layerednavigation_page')->getMetaTitle()) {
                $header = Mage::helper('qm_layerednavigation')->__('Edit Page `%s`', Mage::registry('qm_layerednavigation_page')->getMetaTitle());
            } else {
                $header = Mage::helper('qm_layerednavigation')->__('Edit Page');
            }
        }
        return $header;         
    }
}