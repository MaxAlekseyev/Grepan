<?php

class QM_AdvancedFeedback_Block_Adminhtml_Callback extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller         = 'adminhtml_callback';
        $this->_blockGroup         = 'qmadvfeedback';
        parent::__construct();
        $this->_headerText         = Mage::helper('qmadvfeedback')->__('Callback');
        $this->_updateButton('add', 'label', Mage::helper('qmadvfeedback')->__('Add Callback'));
    }
}
