<?php

abstract class QM_NewPostV2_Model_Abstract extends Mage_Core_Model_Abstract
{
    protected $_helper;

    public function __construct()
    {
        parent::__construct();
        $this->_helper = Mage::helper('qm_newpostv2');
    }

    public function getLocaleDescription()
    {
        $helper = $this->_helper;

        switch ($helper->getLocale()) {
            case $helper::RU_LOCALE:
                return $this->getDescriptionRu();
                break;
            default:
                return $this->getDescription();
        }
    }

    public function loadByLocaleDescription($description)
    {
        $helper = $this->_helper;
        switch ($helper->getLocale()) {
            case $helper::RU_LOCALE:
                return $this->load($description, 'description_ru');
                break;
            default:
                return $this->load($description, 'description');
        }
    }

    public function loadByRef($ref)
    {
        return $this->load($ref, 'ref');
    }
} 