<?php

class QM_SeoCatalog_Model_Category extends Mage_Catalog_Model_Category
{
    public function getName()
    {
        if (Mage::registry('current_category')) {
            if ($this->_getData('alternative_h1')) {
                return $this->_getData('alternative_h1');
            } else {
                return parent::getName();
            }
        } else {
            return parent::getName();
        }
    }
}