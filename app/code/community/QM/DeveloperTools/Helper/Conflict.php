<?php
class QM_DeveloperTools_Helper_Conflict extends Mage_Core_Helper_Abstract {
    public function refreshList() {
        Mage::getResourceModel('qm_devtools/conflict')->truncateTable();
        $tConfigFiles = $this->getConfigFilesList();
        $rewrites     = array();
        foreach ($tConfigFiles as $configFile) {
            $rewrites = $this->getRewriteForFile($configFile, $rewrites);
        }
        foreach ($rewrites as $key => $value) {
            $t          = explode('/', $key);
            $moduleName = $t[0];
            $className  = $t[1];
            $record     = Mage::getModel('qm_devtools/conflict');
            $record->setec_core_module($moduleName);
            $record->setec_core_class($className);
            $rewriteClasses = join(', ', $value);
            $record->setec_rewrite_classes($rewriteClasses);
            if (count($value) > 1) {
                $record->setec_is_conflict(1);
            }
            $record->save();
        }
    }

    public function getConfigFilesList() {
        $retour   = array();
        $codePath = Mage::getBaseDir('app');
        $tmpPath  = Mage::app()->getConfig()->getTempVarDir() . '/ExtensionConflict/';
        if (!is_dir($tmpPath)) {
            mkdir($tmpPath);
        }
        $locations   = array();
        $locations[] = $codePath . DS . 'code' . DS . 'local' . DS;
        $locations[] = $codePath . DS . 'code' . DS . 'community' . DS;
        $locations[] = $tmpPath;
        foreach ($locations as $location) {
            $poolDir = opendir($location);
            while ($namespaceName = readdir($poolDir)) {
                if (!$this->directoryIsValid($namespaceName)) {
                    continue;
                }
                $namespacePath = $location . $namespaceName . '/';
                $namespaceDir  = opendir($namespacePath);
                while ($moduleName = readdir($namespaceDir)) {
                    if (!$this->directoryIsValid($moduleName)) {
                        continue;
                    }
                    $modulePath    = $namespacePath . $moduleName . '/';
                    $configXmlPath = $modulePath . 'etc/config.xml';
                    if (file_exists($configXmlPath)) {
                        $retour[] = $configXmlPath;
                    }
                }
                closedir($namespaceDir);
            }
            closedir($poolDir);
        }

        return $retour;
    }

    private function directoryIsValid($dirName) {
        switch ($dirName) {
            case '.':
            case '..':
            case '':
                return FALSE;
                break;
            default:
                return TRUE;
                break;
        }
    }

    public function getRewriteForFile($configFilePath, $results) {
        $xmlcontent  = file_get_contents($configFilePath);
        $domDocument = new DOMDocument();
        $domDocument->loadXML($xmlcontent);
        foreach ($domDocument->documentElement->getElementsByTagName('rewrite') as $markup) {
            $moduleName = $markup->parentNode->tagName;
            if ($this->manageModule($moduleName)) {
                foreach ($markup->getElementsByTagName('*') as $childNode) {
                    $className    = $childNode->tagName;
                    $rewriteClass = $childNode->nodeValue;
                    $key          = $moduleName . '/' . $className;
                    if (!isset($results[$key])) {
                        $results[$key] = array();
                    }
                    $results[$key][] = $rewriteClass;
                }
            }
        }

        return $results;
    }

    private function manageModule($moduleName) {
        switch ($moduleName) {
            case 'global':
                return FALSE;
                break;
            default:
                return TRUE;
                break;
        }
    }
}
