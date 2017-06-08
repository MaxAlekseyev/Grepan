<?php

/**
 * Source model for available attributes
 *
 * @category    QM
 * @package	    QM_Switcher
 */
class QM_Switcher_Model_Adminhtml_System_Config_Source_Attributes
{
    /**
     * @var string
     */
    protected $_idKey = 'attribute_code';
    /**
     * available options
     * @var null|mixed
     */
    protected $_options = null;
    /**
     * get the list of attributes
     * @access public
     * @param bool $withEmpty
     * @return mixed|null
     */
    public function toOptionArray($withEmpty = true)
    {
        if (is_null($this->_options)) {
            $collection = Mage::getResourceModel('catalog/product_attribute_collection')
                ->addVisibleFilter()
                ->addFieldToFilter('is_configurable', 1)
                ->addFieldToFilter('frontend_input', 'select')
                ->addFieldToFilter('is_global', 1);
                //->addFieldToFilter('is_user_defined', 1);
            ;
            $this->_options = array();
            if ($withEmpty) {
                $this->_options[] = array(
                    'label'=>Mage::helper('qm_switcher')->__('[none]'),
                    'value'=>''
                );
            }
            /** @var Mage_Catalog_Model_Product_Type_Configurable $typeInstance */
            $typeInstance = Mage::getSingleton('catalog/product_type_configurable');
            foreach ($collection as $attribute) {
                /** @var Mage_Catalog_Model_Resource_Eav_Attribute $attribute */
                if ($typeInstance->canUseAttribute($attribute)) {
                    $this->_options[] = array(
                        'label' => $attribute->getFrontendLabel(),
                        'value' => $attribute->getData($this->_idKey)
                    );
                }
            }
        }
        return $this->_options;
    }
}