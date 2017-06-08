<?php

class QM_NewPostV2_Model_System_Config_Source_Warehouse
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = array();

        $currentCity = Mage::getModel('qm_newpostv2/city')->getCollection()->addFilter('ref', Mage::helper('qm_newpostv2')->getCitySender())->getFirstItem();

        $warehouseCollection = Mage::getModel('qm_newpostv2/warehouse')->getCollection()->addFilter('city_id', $currentCity->getId());

        if ($warehouseCollection->getSize()) {
            foreach ($warehouseCollection as $warehouse) {
                array_push($options, array('value' => $warehouse->getRef(), 'label' => $warehouse->getLocaleDescription()));
            }
        } else {
            array_push($options, array('value' => 0, 'label' => Mage::helper('qm_newpostv2')->__('---Sync warehouse---')));
        }

        return $options;
    }
} 