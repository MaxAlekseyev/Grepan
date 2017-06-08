<?php

class QM_Switcher_Block_Adminhtml_Grid_Filter_Switcher extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Abstract implements
    Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Interface
{
    /**
     * @return string
     */
    public function getHtml()
    {
        $product = $this->getProduct();
        $store = $product->getStoreId();
        if ($store) {
            $code = QM_Switcher_Helper_Data::DEFAULT_CONFIGURATION_ATTRIBUTE_CODE;
            $return = '<div style="display:none">';
            $return .= '<input type="checkbox"
                value="'.$code.'"
                id="'.$code.'_default"'.
                ($this->usedDefault() ? ' checked="checked"' : '').
                ' name="use_default[]" />';
            $return .= '<label for="'.$code.'_default">'.
                Mage::helper('qm_switcher')->__('Use Configuration for default store view').
                '</label>';
            $return .= '</div>';
            return $return;
        }
        return '';
    }

    /**
     * @return bool
     */
    public function usedDefault()
    {
        $product = $this->getProduct();
        $attributeCode = QM_Switcher_Helper_Data::DEFAULT_CONFIGURATION_ATTRIBUTE_CODE;
        $defaultValue = $product->getAttributeDefaultValue($attributeCode);

        if (!$product->getExistsStoreValueFlag($attributeCode)) {
            return true;
        } elseif (
            $product->getData(QM_Switcher_Helper_Data::DEFAULT_CONFIGURATION_ATTRIBUTE_CODE) == $defaultValue
            && $product->getStoreId() != Mage_Catalog_Model_Abstract::DEFAULT_STORE_ID
        ) {
            return false;
        }
        return $defaultValue === false;
    }

    /**
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        return Mage::registry('current_product');
    }
}
