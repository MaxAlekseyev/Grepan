<?php

class QM_ExportCatalog_Block_Adminhtml_Config_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('qm_exportcatalog/config')->getCollection();

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $helper = Mage::helper('qm_exportcatalog');

        $this->addColumn('name', array(
            'header' => $helper->__('Name'),
            'index' => 'name'
        ));

        $this->addColumn('url', array(
            'header' => $helper->__('Url'),
            'index' => 'id',
            'renderer' => 'QM_ExportCatalog_Block_Adminhtml_Template_Grid_Renderer_ConfigUrl'
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

} 