<?php

class QM_Core_Model_Product_Image extends Mage_Catalog_Model_Product_Image
{
    public function getBackgroundColor()
    {
        return $this->_backgroundColor;
    }
}