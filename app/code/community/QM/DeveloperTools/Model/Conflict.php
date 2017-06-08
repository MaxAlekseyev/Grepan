<?php
class QM_DeveloperTools_Model_Conflict extends Mage_Core_Model_Abstract {
    private $_rewriteClassesInformation = NULL;

    public function _construct() {
        parent::_construct();
        $this->_init('qm_devtools/conflict');
    }

    public function canFix() {
        $t = explode(',', $this->getec_rewrite_classes());

        return (count($t) <= 2);
    }

    public function getClassInformation1() {
        $a = $this->getRewriteClassesInformation();
        if (count($a) == 2) {
            return $a[0];
        } else {
            return NULL;
        }
    }

    public function getRewriteClassesInformation() {
        if ($this->_rewriteClassesInformation == NULL) {
            $this->_rewriteClassesInformation = array();
            $t                                = explode(',', $this->getec_rewrite_classes());
            foreach ($t as $class) {
                $class                               = trim($class);
                $classArray                          = array();
                $classArray['class']                 = $class;
                $classInfo                           = explode('_', $class);
                $classArray['editor']                = trim($classInfo[0]);
                $classArray['module']                = trim($classInfo[1]);
                $classArray['config_file_path']      = Mage::helper('qm_devtools/extension')->getConfigFilePath($classArray['editor'], $classArray['module']);
                $classArray['class_path']            = Mage::helper('qm_devtools/extension')->getClassPath($class);
                $classArray['class_declaration']     = Mage::helper('qm_devtools/extension')->getClassDeclaration($class);
                $classArray['new_class_declaration'] = 'class ' . $class . ' extends ';
                $this->_rewriteClassesInformation[]  = $classArray;
            }
        }

        return $this->_rewriteClassesInformation;
    }

    public function getClassInformation2() {
        $a = $this->getRewriteClassesInformation();
        if (count($a) == 2) {
            return $a[1];
        } else {
            return NULL;
        }
    }
}