<?php

class QM_NewPostV2_Model_Resource_City_Collection extends QM_NewPostV2_Model_Resource_Abstract_Collection
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('qm_newpostv2/city');
    }

    public function sync()
    {
        try {
            $this->_abstractSync('Address', 'getCities', array(), true, function (&$dataArray) {
                foreach ($dataArray as &$data) {
                    unset($data["Conglomerates"]);
                }
            });
        } catch (Exception $e) {
            $this->_helper->logError('Cannot pass filter on update. Maybe closure invalid');

            return;
        }
    }
} 