<?php

abstract class QM_NewPostV2_Model_Resource_Abstract_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected $_api;
    protected $_helper;

    public function __construct()
    {
        parent::__construct();
        $this->_api    = Mage::helper('qm_newpostv2/api');
        $this->_helper = Mage::helper('qm_newpostv2');
    }

    public function syncFromArray($dataArray, $reload = true)
    {
        if ($reload) {
            $this->truncate();
        }

        $model = Mage::getModel($this->getModelName());

        foreach ($dataArray as $data) {
            $model->setData($data)->save();
        }
    }

    public function truncate()
    {
        foreach ($this as $item) {
            $item->delete();
        }
    }

    public abstract function sync();

    protected function _abstractSync($apiModelName, $methodName, $methodProps = array(), $reload = true, $filter = null)
    {
        $dataArray = $this->_api->call($apiModelName, $methodName, $methodProps);

        if ($filter) {
            try {
                $filter($dataArray);
            } catch (Exception $e) {
                $this->_helper->logError('Cannot use filter on update. Maybe closure invalid ' . $e->getMessage());
                return;
            }
        }

        foreach ($dataArray as $dataKey => $data) {
            $newData = array();

            foreach ($data as $key => $item) {
                $newData[$this->_camelCaseToUnderscore($key)] = $item;
            }

            $dataArray[$dataKey] = $newData;
        }

        $this->syncFromArray($dataArray, $reload);
    }

    private function _camelCaseToUnderscore($str)
    {
        $str[0] = strtolower($str[0]);

        $str = preg_replace_callback("/[A-Z]/", function ($match) {
            foreach ($match as $letter) {
                return '_' . strtolower($letter);
            }

        }, $str);

        return $str;
    }

    public function toJson()
    {
        $dataCollection = array();

        foreach ($this as $item) {
            $data = array();

            $data['ref']         = $item->getRef();
            $data['description'] = $item->getLocaleDescription();

            array_push($dataCollection, $data);
        }

        return json_encode($dataCollection, JSON_FORCE_OBJECT);
    }

}