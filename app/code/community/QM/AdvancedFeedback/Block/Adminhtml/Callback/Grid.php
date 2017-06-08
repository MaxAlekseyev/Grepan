<?php

class QM_AdvancedFeedback_Block_Adminhtml_Callback_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('callbackGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('qmadvfeedback/callback')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('qmadvfeedback')->__('Id'),
            'index'        => 'entity_id',
            'type'        => 'number'
        ));
        $this->addColumn('name', array(
            'header'    => Mage::helper('qmadvfeedback')->__('Name'),
            'align'     => 'left',
            'index'     => 'name',
        ));
        $this->addColumn('telephone', array(
            'header'=> Mage::helper('qmadvfeedback')->__('Telephone'),
            'index' => 'telephone',
            'type'=> 'text',
        ));
        $this->addColumn('status', array(
            'header'     => Mage::helper('qmadvfeedback')->__('Status'),
            'index'      => 'status',
            'type'       => 'options',
            'options'    => Mage::getModel('qmadvfeedback/config_source_status')->getOptions()
        ));

        $this->addColumn('is_spam', array(
            'header'    =>  Mage::helper('customer')->__('Is Spam'),
            'index'     =>  'spam',
            'type'      =>  'options',
            'options'   =>  Mage::getModel('qmadvfeedback/config_source_spam')->getOptions(),
        ));

        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn('store_id', array(
                'header'=> Mage::helper('qmadvfeedback')->__('Store Views'),
                'index' => 'store_id',
                'type'  => 'store',
                'store_all' => true,
                'store_view'=> true,
                'sortable'  => false,
                'filter_condition_callback'=> array($this, '_filterStoreCondition'),
            ));
        }
        $this->addColumn('created_at', array(
            'header'    => Mage::helper('qmadvfeedback')->__('Created at'),
            'index'     => 'created_at',
            'width'     => '120px',
            'type'      => 'datetime',
        ));
        $this->addColumn('updated_at', array(
            'header'    => Mage::helper('qmadvfeedback')->__('Updated at'),
            'index'     => 'updated_at',
            'width'     => '120px',
            'type'      => 'datetime',
        ));
        $this->addColumn('action', array(
            'header'=>  Mage::helper('qmadvfeedback')->__('Action'),
            'width' => '100',
            'type'  => 'action',
            'getter'=> 'getId',
            'actions'   => array(
                array(
                    'caption'   => Mage::helper('qmadvfeedback')->__('Edit'),
                    'url'   => array('base'=> '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter'=> false,
            'is_system'    => true,
            'sortable'  => false,
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('qmadvfeedback')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('qmadvfeedback')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('qmadvfeedback')->__('XML'));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('callback');
        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('qmadvfeedback')->__('Delete'),
            'url'  => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('qmadvfeedback')->__('Are you sure?')
        ));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('qmadvfeedback')->__('Change status'),
            'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'status' => array(
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('qmadvfeedback')->__('Status'),
                        'values' => Mage::getModel('qmadvfeedback/config_source_status')->getOptions()
                )
            )
        ));
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($value);
        return $this;
    }
}
