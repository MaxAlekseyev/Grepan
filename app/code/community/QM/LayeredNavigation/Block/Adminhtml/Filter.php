<?php
class QM_LayeredNavigation_Block_Adminhtml_Filter extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_filter';
        $this->_headerText = Mage::helper('qm_layerednavigation')->__('Manage Filters');
        $this->_blockGroup = 'qm_layerednavigation';
        $this->_addButtonLabel = Mage::helper('qm_layerednavigation')->__('Load');
        parent::__construct();
    }
}