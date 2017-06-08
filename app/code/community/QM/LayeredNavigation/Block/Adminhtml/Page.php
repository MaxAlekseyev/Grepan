<?php
class QM_LayeredNavigation_Block_Adminhtml_Page extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_page';
    $this->_blockGroup = 'qm_layerednavigation';
    $this->_headerText = Mage::helper('qm_layerednavigation')->__('Pages');
    $this->_addButtonLabel = Mage::helper('qm_layerednavigation')->__('Add Page');
    parent::__construct();
  }
}


