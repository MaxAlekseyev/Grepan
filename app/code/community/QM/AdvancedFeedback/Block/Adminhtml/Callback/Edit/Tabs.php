<?php

class QM_AdvancedFeedback_Block_Adminhtml_Callback_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('callback_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('qmadvfeedback')->__('Callback'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_callback', array(
            'label'        => Mage::helper('qmadvfeedback')->__('Callback'),
            'title'        => Mage::helper('qmadvfeedback')->__('Callback'),
            'content'     => $this->getLayout()->createBlock('qmadvfeedback/adminhtml_callback_edit_tab_form')->toHtml(),
        ));
        if (!Mage::app()->isSingleStoreMode()){
            $this->addTab('form_store_callback', array(
                'label'        => Mage::helper('qmadvfeedback')->__('Store views'),
                'title'        => Mage::helper('qmadvfeedback')->__('Store views'),
                'content'     => $this->getLayout()->createBlock('qmadvfeedback/adminhtml_callback_edit_tab_stores')->toHtml(),
            ));
        }
        return parent::_beforeToHtml();
    }

    public function getCallback()
    {
        return Mage::registry('current_callback');
    }
}
