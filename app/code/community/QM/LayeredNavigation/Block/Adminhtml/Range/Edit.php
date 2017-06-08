<?php
class QM_LayeredNavigation_Block_Adminhtml_Range_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id'; 
        $this->_blockGroup = 'qm_layerednavigation';
        $this->_controller = 'adminhtml_range';
    }

    public function getHeaderText()
    {
            return Mage::helper('qm_layerednavigation')->__('Range');
    }
}