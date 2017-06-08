<?php

class QM_Core_Model_System_Config_Backend_JsFiles
{
    const JS_FILE_REGEXP = '/\.js$/';
    public function toOptionArray()
    {
        $jsDir    = Mage::getBaseDir() . DS . 'js' . DS . QM_Core_Helper_Data::NAME_DIR_JS . DS;
        $allFiles = array();

        $directoryIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($jsDir, RecursiveDirectoryIterator::SKIP_DOTS));
        foreach ($directoryIterator as $path => $pathObject) {
            if (preg_match(self::JS_FILE_REGEXP, $path)) {
                $fileName   = substr($path, strlen($jsDir));
                $allFiles[] = array('label' => $fileName, 'value' => $fileName);
            }
        }
        return $allFiles;
    }
}