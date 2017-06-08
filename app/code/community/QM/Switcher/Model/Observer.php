<?php
/**
 * Module observer
 *
 * @category    QM
 * @package	    QM_Switcher
 */
class QM_Switcher_Model_Observer
{
    /**
     * config path to show out of stock configurations
     */
    const XML_SHOW_OUT_OF_STOCK_PATH = 'qm_switcher/settings/out_of_stock';

    /**
     * @return Mage_Catalog_Helper_Product
     */
    protected function _getCatalogHelper()
    {
        return Mage::helper('catalog/product');
    }

    /**
     * @return QM_Switcher_Helper_Data
     */
    protected function _getSwitcherHelper()
    {
        return Mage::helper('qm_switcher');
    }
    /**
     * tell Magento to load out of stock products also
     * @access public
     * @param Varien_Event_Observer $observer
     * @return QM_Switcher_Model_Observer
     */
    public function checkShowStock(Varien_Event_Observer $observer)
    {
        if ($this->_getSwitcherHelper()->isEnabled()) {
            /** @var Mage_Catalog_Model_Product $product */
            $product = $observer->getEvent()->getProduct();
            if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
                Mage::register('old_skip_aleable_check', $this->_getCatalogHelper()->getSkipSaleableCheck());
                $this->_getCatalogHelper()->setSkipSaleableCheck(
                    Mage::getStoreConfigFlag(self::XML_SHOW_OUT_OF_STOCK_PATH)
                );
            }
        }
        return $this;
    }

    /**
     * add column to simple products grid
     * @access public
     * @param Varien_Event_Observer $observer
     * @return QM_Switcher_Model_Observer
     */
    public function addDefaultColumn(Varien_Event_Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Super_Config_Grid) {
            /** @var QM_Switcher_Helper_Data $helper */
            $helper = Mage::helper('qm_switcher');
            if ($helper->isEnabledAdmin()) {
                if (!$block->isReadonly()) {
                    $block->addColumnAfter(
                        'default_combination',
                        array(
                            'header'     => Mage::helper('qm_switcher')->__('Default'),
                            'header_css_class' => 'a-center',
                            'type'      => 'radio',
                            'name'      => 'default_combination',
                            'values'    => $this->_getDefaultConfigurationId(),
                            'align'     => 'center',
                            'index'     => 'entity_id',
                            'html_name' => 'default_combination',
                            'sortable'  => false,
                            'filter'    => 'QM_Switcher_Block_Adminhtml_Grid_Filter_Switcher',
                        ),
                        'in_products'
                    );
                }
            }
        }
        return $this;
    }

    /**
     * get the default configuration
     * @access protected
     * @return array|string
     */
    protected function _getDefaultConfigurationId()
    {
        /** @var Mage_Catalog_Model_Product $product */
        $product = Mage::registry('current_product');
        if ($product) {
            return array($product->getData(QM_Switcher_Helper_Data::DEFAULT_CONFIGURATION_ATTRIBUTE_CODE));
        }
        return '';
    }

    /**
     * Clean generated thumbs for attribute option images
     * @access public
     * @param $observer
     * @return null
     */

    public function cleanOptImages($observer)
    {
        /** @var QM_Switcher_Helper_Optimage $helper */
        $helper = Mage::helper('qm_switcher/optimage');
        $helper->cleanCache();
        return $this;
    }
}
