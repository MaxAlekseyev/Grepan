<?php

/**
 * Banner admin widget chooser
 *
 * @category    QM
 * @package     QM_Banners
 *
 */

class QM_Banners_Block_Adminhtml_Banner_Widget_Chooser extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Block construction, prepare grid params
     *
     * @access public
     * @param array $arguments Object data
     * @return void
     *
     */
    public function __construct($arguments=array())
    {
        parent::__construct($arguments);
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        $this->setDefaultFilter(array('chooser_status' => '1'));
    }

    /**
     * Prepare chooser element HTML
     *
     * @access public
     * @param Varien_Data_Form_Element_Abstract $element Form Element
     * @return Varien_Data_Form_Element_Abstract
     *
     */
    public function prepareElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $uniqId = Mage::helper('core')->uniqHash($element->getId());
        $sourceUrl = $this->getUrl(
            'qm_banners/adminhtml_banners_banner_widget/chooser',
            array('uniq_id' => $uniqId)
        );
        $chooser = $this->getLayout()->createBlock('widget/adminhtml_widget_chooser')
            ->setElement($element)
            ->setTranslationHelper($this->getTranslationHelper())
            ->setConfig($this->getConfig())
            ->setFieldsetId($this->getFieldsetId())
            ->setSourceUrl($sourceUrl)
            ->setUniqId($uniqId);
        if ($element->getValue()) {
            $banner = Mage::getModel('qm_banners/banner')->load($element->getValue());
            if ($banner->getId()) {
                $chooser->setLabel($banner->getName());
            }
        }
        $element->setData('after_element_html', $chooser->toHtml());
        return $element;
    }

    /**
     * Grid Row JS Callback
     *
     * @access public
     * @return string
     *
     */
    public function getRowClickCallback()
    {
        $chooserJsObject = $this->getId();
        $js = '
            function (grid, event) {
                var trElement = Event.findElement(event, "tr");
                var bannerId = trElement.down("td").innerHTML.replace(/^\s+|\s+$/g,"");
                var bannerTitle = trElement.down("td").next().innerHTML;
                '.$chooserJsObject.'.setElementValue(bannerId);
                '.$chooserJsObject.'.setElementLabel(bannerTitle);
                '.$chooserJsObject.'.close();
            }
        ';
        return $js;
    }

    /**
     * Prepare a static blocks collection
     *
     * @access protected
     * @return QM_Banners_Block_Adminhtml_Banner_Widget_Chooser
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
     * Prepare columns for the a grid
     *
     * @access protected
     * @return QM_Banners_Block_Adminhtml_Banner_Widget_Chooser
     *
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'chooser_id',
            array(
                'header' => Mage::helper('qm_banners')->__('Id'),
                'align'  => 'right',
                'index'  => 'entity_id',
                'type'   => 'number',
                'width'  => 50
            )
        );

        $this->addColumn(
            'chooser_name',
            array(
                'header' => Mage::helper('qm_banners')->__('Name'),
                'align'  => 'left',
                'index'  => 'name',
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
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
            'chooser_status',
            array(
                'header'  => Mage::helper('qm_banners')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    0 => Mage::helper('qm_banners')->__('Disabled'),
                    1 => Mage::helper('qm_banners')->__('Enabled')
                ),
            )
        );
        return parent::_prepareColumns();
    }

    /**
     * get url for grid
     *
     * @access public
     * @return string
     *
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            'adminhtml/banners_banner_widget/chooser',
            array('_current' => true)
        );
    }

    /**
     * after collection load
     *
     * @access protected
     * @return QM_Banners_Block_Adminhtml_Banner_Widget_Chooser
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
