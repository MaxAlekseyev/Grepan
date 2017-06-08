<?php

class QM_ExportCatalog_Block_Adminhtml_Config extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        parent::__construct();

        $this->_blockGroup = 'qm_exportcatalog';
        $this->_controller = 'adminhtml_config';
        $this->_headerText = Mage::helper('qm_exportcatalog')->__('Export catalog config');

    }

}