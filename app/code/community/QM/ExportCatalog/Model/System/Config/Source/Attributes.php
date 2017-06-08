<?php

class QM_ExportCatalog_Model_System_Config_Source_Attributes
{
    /**
     * Create array to admin system config of module
     * @return array all attributes
     */
    public function toOptionArray()
    {
        $optionArray = array();

        $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
            ->getItems();

        foreach ($attributes as $attribute) {
            $option = array();
            $code   = $attribute->getAttributecode();
            $label  = $attribute->getFrontendLabel();

            $option['value'] = $code;
            if ($label) {
                $option['label'] = $label;
            } else {
                $option['label'] = $code;
            }

            array_push($optionArray, $option);
        }
        return $optionArray;
    }
} 