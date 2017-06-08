<?php

class QM_LayeredNavigation_Helper_Attributes
{
    protected $_optionsHash;
    protected $_attributes;
    protected $_options;
    protected $_optionsLabels = array();

    const SWITCHER_MEDIA_DIR = 'qm/optimages';
    const SWITCHER_CONFIG = 'qm_switcher/settings/transform';

    /**
     * @return array
     */
    public function getAllFilterableOptionsAsHash()
    {
        if (is_null($this->_optionsHash)) {
            $hash       = array();
            $attributes = $this->getFilterableAttributes();

            $helper = Mage::helper('qm_layerednavigation/url');

            $options = $this->getAllOptions();

            foreach ($attributes as $a) {
                $code        = $a->getAttributeCode();
                $code        = str_replace(array('_', '-'), Mage::getStoreConfig('qm_layerednavigation/seo/special_char'), $code);
                $hash[$code] = array();
                foreach ($options as $o) {
                    if ($o['value'] && $o['attribute_id'] == $a->getId()) { // skip first empty
                        $unKey = $helper->createKey($o['value']);
                        while (isset($hash[$code][$unKey])) {
                            $unKey .= '_';
                        }
                        $hash[$code][$unKey] = $o['option_id'];
                        /*
                         * Keep original label for further use
                         */
                        $this->_optionsLabels[$o['option_id']] = $o['value'];
                    }
                }
            }
            $this->_optionsHash = $hash;
        }

        return $this->_optionsHash;
    }

    public function getFilterableAttributes()
    {
        if (is_null($this->_attributes)) {
            $collection = Mage::getResourceModel('catalog/product_attribute_collection');
            $collection
                ->setItemObjectClass('catalog/resource_eav_attribute')
                ->addStoreLabel(Mage::app()->getStore()->getId())
                ->addIsFilterableFilter()
                ->setOrder('position', 'ASC');
            $collection->load();
            $this->_attributes = $collection;
        }

        return $this->_attributes;
    }

    /**
     * Get option for specific attribute
     * @param string $attributeCode
     * @return array
     */
    public function getAttributeOptions($attributeCode)
    {
        $options       = array();
        $all           = $this->getAllFilterableOptionsAsHash();
        $attributeCode = str_replace(array('_', '-'), Mage::getStoreConfig('qm_layerednavigation/seo/special_char'), $attributeCode);
        if (isset($all[$attributeCode])) {
            $attributeOptions = $all[$attributeCode];
            foreach ($attributeOptions as $label => $value) {
                $options[] = array(
                    'value' => $value,
                    'label' => $this->_optionsLabels[$value]
                );
            }
        }

        return $options;
    }

    protected function getAllOptions()
    {
        if (is_null($this->_options)) {
            $valuesCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
                ->setStoreFilter()
                ->toArray();
            $this->_options   = $valuesCollection['items'];
        }

        return $this->_options;
    }

    public function getFilterCssClass(Mage_Catalog_Model_Layer_Filter_Abstract $filter)
    {
        return 'attr-' . $filter->getRequestVar();
    }

    public function getUrlFromChooser(Mage_Catalog_Model_Layer_Filter_Item $option)
    {
        if (Mage::helper('qm_layerednavigation')->isColorSwitherEnabled()) {
            $config = $this->_getChooserConfig();

            $images = new Varien_Object(Mage::helper('core')->jsonDecode($config->getImage()));

            if ($images->getData($option->getOptionId())) {
                return Mage::getBaseUrl('media') . self::SWITCHER_MEDIA_DIR . $images[$option->getOptionId()];
            }
        }
    }

    public function getHexFromChooser(Mage_Catalog_Model_Layer_Filter_Item $option)
    {
        if (Mage::helper('qm_layerednavigation')->isColorSwitherEnabled()) {
            $config = $this->_getChooserConfig();

            $colors = new Varien_Object(Mage::helper('core')->jsonDecode($config->getColor()));

            return $colors->getData($option->getOptionId());
        }
    }

    private function _getChooserConfig()
    {
        $configJson = Mage::getStoreConfig(self::SWITCHER_CONFIG);
        $colorId    = Mage::getModel('eav/entity_attribute')
            ->getCollection()->addFieldToFilter('attribute_code', array('in' => 'color'))
            ->getFirstItem()->getAttributeId();

        $config = new Varien_Object(Mage::helper('core')->jsonDecode($configJson));

        return new Varien_Object($config->getData('options/'.$colorId));
    }
}