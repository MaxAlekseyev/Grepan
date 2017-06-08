<?php

class QM_ExportCatalog_Model_Observer
{
    public function exportAll()
    {
        $helper =  Mage::helper('qm_exportcatalog');
        $helper->log($helper->__('Run cron'));

        $configs = Mage::getModel('qm_exportcatalog/config')->getCollection()
            ->addFieldToFilter('is_active', 1);

        foreach ($configs as $config) {
            Mage::helper('qm_exportcatalog/export')->exportToYml($config);
        }
    }
}