<?php

class QM_Core_Model_System_Config_Backend_CssFiles
{
    const CSS_FILE_REGEXP = '/\.css$/';

    public function toOptionArray()
    {
        $cssDir = Mage::helper('qmcore')->getCssDirPath();

        $allFiles = array();

        $directoryIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($cssDir, RecursiveDirectoryIterator::SKIP_DOTS));
        foreach ($directoryIterator as $path => $pathObject) {
            if (preg_match(self::CSS_FILE_REGEXP, $path)) {
                $fileName   = substr($path, strlen($cssDir));
                $allFiles[] = array('label' => $fileName, 'value' => $fileName);
            }
        }

        array_unshift($allFiles, array('label' => '', 'value' => ''));

        return $allFiles;
    }
}