<?php

/**
 * Banner admin block
 *
 * @category    QM
 * @package     QM_Banners
 *
 */
class QM_Banners_Block_Adminhtml_Banner extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     *
     */
    public function __construct()
    {
        $this->_controller         = 'adminhtml_banner';
        $this->_blockGroup         = 'qm_banners';
        parent::__construct();
        $this->_headerText         = Mage::helper('qm_banners')->__('Banner');
        $this->_updateButton('add', 'label', Mage::helper('qm_banners')->__('Add Banner'));

    }
}
