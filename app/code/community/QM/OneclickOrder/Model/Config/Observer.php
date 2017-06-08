<?php

class QM_OneclickOrder_Model_Config_Observer {
    public function changeProductAttrDefault()
    {
        $attribute = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setCodeFilter('enable_one_click_order')
            ->getFirstItem();

        $attribute->setDefaultValue(Mage::getStoreConfig('qmoneclickorder/general/default_enable_on_new_product'));
        $attribute->save();
    }
}