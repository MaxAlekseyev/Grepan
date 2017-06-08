<?php

class QM_DeveloperTools_Helper_Extension extends Mage_Core_Helper_Abstract {
    
    public function getConfigFilePath($editor, $module) {
        $moduleInfo = Mage::getConfig()->getModuleConfig($editor . '_' . $module);
        $moduleInfo = $moduleInfo->asArray();
        $path       = Mage::getBaseDir('base') . DS . 'app' . DS . 'code' . DS . $moduleInfo['codePool'] . DS . $editor . DS . $module . DS . 'etc' . DS . 'config.xml';

        return $path;
    }

    public function getClassPath($class) {
        $classArray = explode('_', $class);
        $editor     = trim($classArray[0]);
        $module     = trim($classArray[1]);
        $moduleInfo = Mage::getConfig()->getModuleConfig($editor . '_' . $module);
        $moduleInfo = $moduleInfo->asArray();
        $path = Mage::getBaseDir('base') . DS . 'app' . DS . 'code' . DS . $moduleInfo['codePool'] . DS . str_replace('_', DS, $class) . '.php';

        return $path;
    }

    public function getClassDeclaration($class) {
        $obj         = new $class();
        $ref         = new ReflectionObject($obj);
        $parentClass = $ref->getParentClass()->getname();
        $declaration = 'class ' . $class . ' extends ' . $parentClass;

        return $declaration;
    }
}