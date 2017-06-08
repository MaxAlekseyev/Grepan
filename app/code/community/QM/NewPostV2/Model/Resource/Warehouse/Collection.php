<?php

class QM_NewPostV2_Model_Resource_Warehouse_Collection extends QM_NewPostV2_Model_Resource_Abstract_Collection
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('qm_newpostv2/warehouse');
    }

    public function sync()
    {
        $cityCollection = Mage::getModel('qm_newpostv2/city')->getCollection();

        $reload = true;

        try {
            foreach ($cityCollection as $city) {
                $this->_abstractSync('Address'
                    , 'getWarehouses'
                    , array('CityRef' => $city->getRef())
                    , $reload
                    , function (&$dataArray) use ($city) {
                        foreach ($dataArray as &$data) {
                            $data['CityId'] = $city->getId();
                        }
                    }
                );

                $reload = false;
            }
        } catch (Exception $e) {
            $this->_helper->logError('Cannot pass filter on update. Maybe closure invalid');

            return;
        }
    }

    public function filterByCity($city)
    {
        if (is_object($city)) {
            $cityId = $city->getId();
        } else {
            $cityId = $city;
        }

        return $this->addFilter('city_id', array('in' => $cityId));
    }
} 