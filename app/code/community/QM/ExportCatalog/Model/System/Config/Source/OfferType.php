<?php

class QM_ExportCatalog_Model_System_Config_Source_OfferType
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('qm_exportcatalog');
        return array(
            array('value' => $helper::OFFER_TYPE_SIMPLE, 'label'=>Mage::helper('qm_exportcatalog')->__('simple')),
            array('value' => $helper::OFFER_TYPE_VENDOR_MODEL, 'label'=>Mage::helper('qm_exportcatalog')->__('vendor.model')),
            array('value' => $helper::OFFER_TYPE_SMART, 'label'=>Mage::helper('qm_exportcatalog')->__('smart'))
        );
    }
} 