<?php

class QM_ExportCatalog_Block_Adminhtml_ShowErrors extends Mage_Core_Block_Template
{
    protected function getErrors()
    {
        $helper = Mage::helper('qm_exportcatalog');
        $lastErrorLog = Mage::getBaseDir('log') .'/'. $helper::LAST_ERRORS_LOG_FILE_NAME;

        if(!file_exists($lastErrorLog)) {
            return null;
        }

        $lines = array();

        $handle = fopen($lastErrorLog, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                array_push($lines, $line);
            }
        }

        return $lines;
    }
} 