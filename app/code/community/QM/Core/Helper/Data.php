<?php

class QM_Core_Helper_Data extends Mage_Core_Helper_Abstract
{
    const NAME_DIR_CSS = 'css/qmcore';
    const NAME_DIR_JS  = 'qmcore';
    //const PLUGIN_DIR_JS = 'plugins';

    /*protected $_filesJs = array (
        'jquery-1.10.2.min.js',
        'jquery.noconflict.js',
        'jquery-migrate-1.2.1.min.js',
        'jquery-ui-1.10.4.min.js',
        'jquery.mousewheel.min.js',
        'jquery.fancybox.pack.js',
        'jquery.chosen.min.js',
        'jquery.bxslider.min.js',
        'jquery.mask.min.js',
        'floating-popup.js',
        'local.js'
    );*/

    /*protected $_filesCss = array(
        'smoothness/jquery-ui-1.10.4.min.css',
        'fancybox/jquery.fancybox.css',
        'chosen/chosen.css',
        'bxslider/jquery.bxslider.css',
        'floating-popup/styles.css'
    );*/

    /*protected function includePlugins()
    {
        if (!defined('PLUGIN_DIR_JS')) {
            return false;
        }
        $dirPath = Mage::getBaseDir() . DS . 'js' . DS . self::NAME_DIR_JS . DS . self::PLUGIN_DIR_JS;
        if(is_dir($dirPath)) {
            if($dh = opendir($dirPath)) {
                while(($file = readdir($dh)) !== false) {

                    if(pathinfo($file, PATHINFO_EXTENSION) == 'js') {
                        array_push($this->_filesJs, self::PLUGIN_DIR_JS . '/' . $file);
                    }
                }
                closedir($dh);
            }
        }
    }*/

    /*public function getJQueryPath($file)
    {
        return self::NAME_DIR_JS . '/' . $file;
    }*/

    public function getCssPath($file)
    {
        return self::NAME_DIR_CSS . '/' . $file;
    }

    public function addJsAtEnd()
    {
        return Mage::getStoreConfig('qmcore/add_js/at_end');
    }

    public function getFilesJsConfig()
    {
        $configs    = array();
        $fileConfig = preg_split('/,(?={)/', Mage::getStoreConfig('qmcore/add_js/js_files'), -1, PREG_SPLIT_DELIM_CAPTURE);

        try {
        	foreach ($fileConfig as $config) {
         	   $configs[] = Zend_Json::decode($config);
        	}
    	} catch (Exception $e) {

    	}

        return $configs;
    }

    public function getFilesJs()
    {
        //$this->includePlugins();
        $filePathes = array();
        $fileConfig = $this->getFilesJsConfig();

        foreach ($fileConfig as $config) {
            $file     = $config['value'];
            if ($config['selected']) {
                $path = Mage::getBaseDir() . DS . 'js' . DS . self::NAME_DIR_JS . DS . $file;

                if ($file && file_exists($path)) {
                    $filePathes[] = self::NAME_DIR_JS . DS . $file;
                }
            }
        }

        return $filePathes;
    }

    public function getCssDirPath()
    {
        return Mage::getBaseDir('skin') . DS . 'frontend' . DS . 'qmage' . DS . 'default' . DS . QM_Core_Helper_Data::NAME_DIR_CSS . DS;
    }

    public function getFilesCss()
    {
        $fileConfig = explode(',', Mage::getStoreConfig('qmcore/add_css/css_files'));
        $filePathes = array();

        foreach ($fileConfig as $config) {
            if ($config && file_exists($this->getCssDirPath() . $config)) {
                $filePathes[] = $config;
            }
        }

        return $filePathes;
    }

    public function getResizedImage($path, $pathCache, $fileName, $newFileName, $width, $height, $quality = 100)
    {
        $imageUrl = Mage::getBaseDir('media') . DS . $path . DS . $fileName;
        if (!is_file($imageUrl))
            return false;

        $imageResized = Mage::getBaseDir('media') . DS . $pathCache . DS . $newFileName;
        if (!file_exists($imageResized) && file_exists($imageUrl) || file_exists($imageUrl) && filemtime($imageUrl) > filemtime($imageResized)) {
            $imageObj = new Varien_Image ($imageUrl);
            $imageObj->constrainOnly(false);
            $imageObj->keepFrame(true);
            $imageObj->backgroundColor(array(255, 255, 255));
            $imageObj->keepTransparency(true);
            $imageObj->keepAspectRatio(true);
            $imageObj->quality($quality);
            $imageObj->resize($width, $height);
            $imageObj->save($imageResized);
        }

        if (file_exists($imageResized)) {
            return Mage::getBaseUrl('media') . $pathCache . "/" . $newFileName;
        } else {
            return Mage::getBaseUrl('media') . $path . "/" . $fileName;
        }
    }

    public function getRelativePath($url)
    {
        return str_replace(Mage::getUrl('/'), '/', $url);
    }

    public function isForceRemoveFromStore()
    {
        return Mage::getStoreConfig(
            "qmcore/change_store_view/force_remove_from_store"
        );
    }

    public function isCompareBlockDisabled()
    {
        return Mage::getStoreConfig(
            "qmcore/disable_blocks/disable_compare"
        );
    }

    public function pluralize($n, $str = null, $format = null)
    {
        if ($str === null)
            return $n;
        if (!is_array($str))
            $str = explode(',', $str);
        if (count($str) == 2)
            $str[2] = $str[1];

        $n     = (int)$n;
        $cases = array(2, 0, 1, 1, 1, 2);
        $str   = $str[($n % 100 > 4 && $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]];

        if ($format != null && is_string($format)) {
            return str_replace(array('%s', '%n'), array($str, $n), $format);
        }
        return $n . ' ' . $str;
    }
}