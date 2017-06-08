<?php

class QM_OneclickOrder_Block_Adminhtml_Order_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('order_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('qmoneclickorder')->__('OneClick Order'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_order', array(
            'label'        => Mage::helper('qmoneclickorder')->__('Order'),
            'title'        => Mage::helper('qmoneclickorder')->__('Order'),
            'content'     => $this->getLayout()->createBlock('qmoneclickorder/adminhtml_order_edit_tab_form')->toHtml(),
        ));
        if (!Mage::app()->isSingleStoreMode()){
            $this->addTab('form_store_order', array(
                'label'        => Mage::helper('qmoneclickorder')->__('Store views'),
                'title'        => Mage::helper('qmoneclickorder')->__('Store views'),
                'content'     => $this->getLayout()->createBlock('qmoneclickorder/adminhtml_order_edit_tab_stores')->toHtml(),
            ));
        }
        return parent::_beforeToHtml();
    }

    public function getOrder()
    {
        return Mage::registry('current_oneclickorder');
    }
}
