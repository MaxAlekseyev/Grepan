<?php

class QM_NewPostV2_Model_System_Config_Source_City
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = array();

        $cityCollection = Mage::getModel('qm_newpostv2/city')->getCollection();

        if ($cityCollection->getSize()) {
            foreach ($cityCollection as $city) {
                array_push($options, array('value' => $city->getRef(), 'label' => $city->getLocaleDescription()));
            }
        } else {
            array_push($options, array('value' => 0, 'label' => Mage::helper('qm_newpostv2')->__('---Sync city---')));
        }

        return $options;
    }
} 