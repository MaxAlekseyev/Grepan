<?php

class QM_NewPostV2_Model_System_Config_Source_Locale
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('qm_newpostv2');

        return array(
            array('value' => $helper::RU_LOCALE, 'label' => $helper->__('Russian')),
            array('value' => $helper::UA_LOCALE, 'label' => $helper->__('Ukrainian'))
        );
    }
} 