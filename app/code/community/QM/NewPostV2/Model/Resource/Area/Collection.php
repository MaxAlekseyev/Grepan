<?php

class QM_NewPostV2_Model_Resource_Area_Collection extends QM_NewPostV2_Model_Resource_Abstract_Collection
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('qm_newpostv2/area');
    }

    public function sync()
    {
        $this->_abstractSync('Address', 'getAreas');
    }
} 