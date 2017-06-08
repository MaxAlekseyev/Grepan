<?php

class QM_OneclickOrder_Block_Adminhtml_Order extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller         = 'adminhtml_order';
        $this->_blockGroup         = 'qmoneclickorder';
        parent::__construct();
        $this->_headerText         = Mage::helper('qmoneclickorder')->__('Order');
        //$this->_updateButton('add', 'label', Mage::helper('qmoneclickorder')->__('Add Order'));
        $this->removeButton('add');
    }
}
