<?php

class QM_AdvancedFeedback_Block_Adminhtml_Consultation_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('consultation_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('qmadvfeedback')->__('Сonsultation'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_consultation', array(
            'label'        => Mage::helper('qmadvfeedback')->__('Сonsultation'),
            'title'        => Mage::helper('qmadvfeedback')->__('Сonsultation'),
            'content'     => $this->getLayout()->createBlock('qmadvfeedback/adminhtml_consultation_edit_tab_form')->toHtml(),
        ));
        if (!Mage::app()->isSingleStoreMode()){
            $this->addTab('form_store_consultation', array(
                'label'        => Mage::helper('qmadvfeedback')->__('Store views'),
                'title'        => Mage::helper('qmadvfeedback')->__('Store views'),
                'content'     => $this->getLayout()->createBlock('qmadvfeedback/adminhtml_consultation_edit_tab_stores')->toHtml(),
            ));
        }
        return parent::_beforeToHtml();
    }

    public function getConsultation()
    {
        return Mage::registry('current_consultation');
    }
}
