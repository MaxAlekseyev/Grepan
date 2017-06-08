<?php

class QM_NewPostV2_Model_Street extends QM_NewPostV2_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('qm_newpostv2/street');
    }

    //no locale descriptions
    public function getLocaleDescription()
    {
        return $this->getDescription();
    }

    //no locale descriptions
    public function loadByLocaleDescription($description)
    {
        return $this->load($description, 'description');
    }
} 