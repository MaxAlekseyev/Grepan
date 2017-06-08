<?php

/**
 * Banner admin grid block
 *
 * @category    QM
 * @package     QM_Banners
 *
 */
class QM_Banners_Block_Adminhtml_Banner_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('bannerGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return QM_Banners_Block_Adminhtml_Banner_Grid
     *
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('qm_banners/banner')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return QM_Banners_Block_Adminhtml_Banner_Grid
     *
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('qm_banners')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'name',
            array(
                'header'    => Mage::helper('qm_banners')->__('Name'),
                'align'     => 'left',
                'index'     => 'name',
            )
        );
        
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('qm_banners')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('qm_banners')->__('Enabled'),
                    '0' => Mage::helper('qm_banners')->__('Disabled'),
                )
            )
        );
        $this->addColumn(
            'width',
            array(
                'header' => Mage::helper('qm_banners')->__('Width'),
                'index'  => 'width',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'height',
            array(
                'header' => Mage::helper('qm_banners')->__('Height'),
                'index'  => 'height',
                'type'=> 'number',

            )
        );
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn(
                'store_id',
                array(
                    'header'     => Mage::helper('qm_banners')->__('Store Views'),
                    'index'      => 'store_id',
                    'type'       => 'store',
                    'store_all'  => true,
                    'store_view' => true,
                    'sortable'   => false,
                    'filter_condition_callback'=> array($this, '_filterStoreCondition'),
                )
            );
        }
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('qm_banners')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('qm_banners')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('qm_banners')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('qm_banners')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('qm_banners')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return QM_Banners_Block_Adminhtml_Banner_Grid
     *
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('banner');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('qm_banners')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('qm_banners')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('qm_banners')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('qm_banners')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('qm_banners')->__('Enabled'),
                            '0' => Mage::helper('qm_banners')->__('Disabled'),
                        )
                    )
                )
            )
        );
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param QM_Banners_Model_Banner
     * @return string
     *
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     *
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return QM_Banners_Block_Adminhtml_Banner_Grid
     *
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    /**
     * filter store column
     *
     * @access protected
     * @param QM_Banners_Model_Resource_Banner_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return QM_Banners_Block_Adminhtml_Banner_Grid
     *
     */
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($value);
        return $this;
    }
}
