<?php

class QM_AdvancedFeedback_Block_Adminhtml_Consultation extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller         = 'adminhtml_consultation';
        $this->_blockGroup         = 'qmadvfeedback';
        parent::__construct();
        $this->_headerText         = Mage::helper('qmadvfeedback')->__('Сonsultation');
        $this->_updateButton('add', 'label', Mage::helper('qmadvfeedback')->__('Add Сonsultation'));
    }
}
