<?php
class QM_LayeredNavigation_Block_Adminhtml_Filter_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('filterTabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('qm_layerednavigation')->__('Filter Properties'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('general', array(
            'label'     => Mage::helper('qm_layerednavigation')->__('General'),
            'content'   => $this->getLayout()->createBlock('qm_layerednavigation/adminhtml_filter_edit_tab_general')->toHtml(),
        ));
        
        /*
         * Add options tab only for decimals
         */         
        if (Mage::registry('qm_layerednavigation_filter')->getBackendType() != 'decimal') {
            $this->addTab('values', array(
                'label'     => Mage::helper('qm_layerednavigation')->__('Options'),
                'class'     => 'ajax',
                'url'       => $this->getUrl('*/*/values', array('_current' => true)),
            ));
        }

        $this->_updateActiveTab();
        return parent::_beforeToHtml();
    }
    
    protected function _updateActiveTab()
    {
        $tabId = $this->getRequest()->getParam('tab');
        if ($tabId) {
            $tabId = preg_replace("#{$this->getId()}_#", '', $tabId);
            if ($tabId) {
                $this->setActiveTab($tabId);
            }
        }
        else {
           $this->setActiveTab('general'); 
        }
    }
}