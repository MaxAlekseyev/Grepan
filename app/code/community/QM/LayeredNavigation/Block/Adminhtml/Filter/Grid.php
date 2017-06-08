<?php
class QM_LayeredNavigation_Block_Adminhtml_Filter_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('filtersGrid');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('qm_layerednavigation/filter')->getResourceCollection()
            ->addTitles();
            
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $yesno = array(Mage::helper('catalog')->__('No'), Mage::helper('catalog')->__('Yes'));
        
        $blockpos = array(
            'left' => Mage::helper('catalog')->__('Sidebar'), 
            'top'  => Mage::helper('catalog')->__('Top')
        );
        
        
        $this->addColumn('filter_id', array(
            'header'    => Mage::helper('qm_layerednavigation')->__('ID'),
            'align'     => 'right',
            'width'     => '50px',
            'index'     => 'filter_id',
        ));

        $this->addColumn('position', array(
            'header'    => Mage::helper('qm_layerednavigation')->__('Position'),
            'align'     => 'left',
            'index'     => 'position',
            'width'     => '50px',
        ));
        
        $this->addColumn('frontend_label', array(
            'header'    => Mage::helper('qm_layerednavigation')->__('Attribute'),
            'align'     => 'left',
            'index'     => 'frontend_label',
        ));
        
        $this->addColumn('block_pos', array(
            'header'    => Mage::helper('qm_layerednavigation')->__('Show in the Block'),
            'align'     => 'left',
            'index'     => 'block_pos',
            'type'        => 'options',
            'options'   => $blockpos
        ));
        
        $this->addColumn('display_type', array(
            'header'    => Mage::helper('qm_layerednavigation')->__('Display Type'),
            'align'     => 'left',
            'index'     => 'display_type',
            'getter'     => 'getDisplayTypeString',
            'options'     => Mage::helper('qm_layerednavigation')->getDisplayTypes(),
            'filter'     => false,
            'sortable'    => false,
        ));
        
        $this->addColumn('hide_counts', array(
            'header'    => Mage::helper('qm_layerednavigation')->__('Hide Quantities'),
            'align'     => 'left',
            'index'     => 'hide_counts',
            'type'        => 'options',
            'options'    => $yesno,
        ));
        
        $this->addColumn('collapsed', array(
            'header'    => Mage::helper('qm_layerednavigation')->__('Collapsed'),
            'align'     => 'left',
            'index'     => 'collapsed',
            'type'        => 'options',
            'options'    => $yesno,
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('filter_id');
        $this->getMassactionBlock()->setFormFieldName('filter_id');
        
        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('qm_layerednavigation')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('qm_layerednavigation')->__('Are you sure?')
        ));
        
        return $this; 
  }

}