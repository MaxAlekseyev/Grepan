<?php

class QM_Core_Model_Core_Layout extends Mage_Core_Model_Layout
{
    protected function _generateAction($node, $parent)
    {
        $compiler = Mage::getModel('qmcore/compiler');

        if (isset($node['ifconfig']) && ($configPath = (string)$node['ifconfig'])) {
            $condition = true;
            if (isset($node['condition'])) {
                $condition = $compiler->getXmlCondition($compiler->spaceRemover($node['condition']));
            }
            $config = $compiler->getAdminConfig($compiler->spaceRemover($configPath));

            if ($config !== $condition) {
                return $this;
            }
        } else if (isset($node['modules']) && isset($node['options'])) {
            $finalResult = false;
            $extracted = $compiler->extractor($node);
            $operation = $compiler->spaceRemover((string)$node['operation']);
            $valideOpe = $operation != '' ? $compiler->validator($node) : true;

            if ($valideOpe) {
                $tokens = $compiler->getToken($operation);
                $finalResult = $compiler->operation($extracted, $tokens);
            }
            if ($finalResult !== true) {
                return $this;
            }
        }

        $this->_runAction($node, $parent);
    }

    private function _runAction($node, $parent)
    {
        $method = (string)$node['method'];
        if (!empty($node['block'])) {
            $parentName = (string)$node['block'];
        } else {
            $parentName = $parent->getBlockName();
        }

        $_profilerKey = 'BLOCK ACTION: ' . $parentName . ' -> ' . $method;
        Varien_Profiler::start($_profilerKey);

        if (!empty($parentName)) {
            $block = $this->getBlock($parentName);
        }
        if (!empty($block)) {

            $args = (array)$node->children();
            unset($args['@attributes']);

            foreach ($args as $key => $arg) {
                if (($arg instanceof Mage_Core_Model_Layout_Element)) {
                    if (isset($arg['helper'])) {
                        $helperName = explode('/', (string)$arg['helper']);
                        $helperMethod = array_pop($helperName);
                        $helperName = implode('/', $helperName);
                        $arg = $arg->asArray();
                        unset($arg['@']);
                        $args[$key] = call_user_func_array(array(Mage::helper($helperName), $helperMethod), $arg);
                    } else {
                        $arr = array();
                        foreach ($arg as $subkey => $value) {
                            $arr[(string)$subkey] = $value->asArray();
                        }
                        if (!empty($arr)) {
                            $args[$key] = $arr;
                        }
                    }
                }
            }

            if (isset($node['json'])) {
                $json = explode(' ', (string)$node['json']);
                foreach ($json as $arg) {
                    $args[$arg] = Mage::helper('core')->jsonDecode($args[$arg]);
                }
            }

            $this->_translateLayoutNode($node, $args);
            call_user_func_array(array($block, $method), $args);
        }

        Varien_Profiler::stop($_profilerKey);

        return $this;
    }
}
