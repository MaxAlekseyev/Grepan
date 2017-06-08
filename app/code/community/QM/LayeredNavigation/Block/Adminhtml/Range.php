<?php
class QM_LayeredNavigation_Block_Adminhtml_Range extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_range';
    $this->_blockGroup = 'qm_layerednavigation';
    $this->_headerText = Mage::helper('qm_layerednavigation')->__('Ranges');
    $this->_addButtonLabel = Mage::helper('qm_layerednavigation')->__('Add Range');
    parent::__construct();
  }
}