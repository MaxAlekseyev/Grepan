<?php

class QM_OneclickOrder_Block_Adminhtml_Order_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('orderGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('qmoneclickorder/order')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('qmoneclickorder')->__('Id'),
            'index'        => 'entity_id',
            'type'        => 'number'
        ));
        $this->addColumn('product_sku', array(
            'header'     => Mage::helper('qmoneclickorder')->__('Product Sku'),
            'index'      => 'product_sku',
            'type'       => 'text'
        ));
        $this->addColumn('product_name', array(
            'header'     => Mage::helper('qmoneclickorder')->__('Product Name'),
            'index'      => 'product_name',
            'type'       => 'text'
        ));
        $store = $this->_getStore();
        $this->addColumn('product_price', array(
            'header'=> Mage::helper('qmoneclickorder')->__('Product Price'),
            'type'  => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode(),
            'index' => 'product_price',
        ));
        $this->addColumn('telephone', array(
            'header'=> Mage::helper('qmoneclickorder')->__('Telephone'),
            'index' => 'telephone',
            'type'=> 'text',
        ));
        $this->addColumn('email', array(
            'header'=> Mage::helper('qmoneclickorder')->__('Email'),
            'index' => 'email',
            'type'=> 'text',
        ));
        $this->addColumn('status', array(
            'header'     => Mage::helper('qmoneclickorder')->__('Status'),
            'index'      => 'status',
            'type'       => 'options',
            'options'    => Mage::getModel('qmoneclickorder/config_source_status')->getOptions()
        ));
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn('store_id', array(
                'header'=> Mage::helper('qmoneclickorder')->__('Store Views'),
                'index' => 'store_id',
                'type'  => 'store',
                'store_all' => true,
                'store_view'=> true,
                'sortable'  => false,
                'filter_condition_order'=> array($this, '_filterStoreCondition'),
            ));
        }
        $this->addColumn('created_at', array(
            'header'    => Mage::helper('qmoneclickorder')->__('Created at'),
            'index'     => 'created_at',
            'width'     => '120px',
            'type'      => 'datetime',
        ));
        $this->addColumn('updated_at', array(
            'header'    => Mage::helper('qmoneclickorder')->__('Updated at'),
            'index'     => 'updated_at',
            'width'     => '120px',
            'type'      => 'datetime',
        ));
        $this->addColumn('action', array(
            'header'=>  Mage::helper('qmoneclickorder')->__('Action'),
            'width' => '100',
            'type'  => 'action',
            'getter'=> 'getId',
            'actions'   => array(
                array(
                    'caption'   => Mage::helper('qmoneclickorder')->__('Edit'),
                    'url'   => array('base'=> '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter'=> false,
            'is_system'    => true,
            'sortable'  => false,
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('qmoneclickorder')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('qmoneclickorder')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('qmoneclickorder')->__('XML'));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('order');
        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('qmoneclickorder')->__('Delete'),
            'url'  => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('qmoneclickorder')->__('Are you sure?')
        ));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('qmoneclickorder')->__('Change status'),
            'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'status' => array(
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('qmoneclickorder')->__('Status'),
                        'values' => Mage::getModel('qmoneclickorder/config_source_status')->getOptions()
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
