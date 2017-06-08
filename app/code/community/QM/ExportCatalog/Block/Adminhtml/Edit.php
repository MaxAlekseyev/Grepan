<?php

class QM_ExportCatalog_Block_Adminhtml_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'qm_exportcatalog';
        $this->_controller = 'adminhtml_edit';
        $this->_mode       = 'edit';
        $this->_headerText = Mage::helper('qm_exportcatalog')->__('Edit catalog config');
    }

}