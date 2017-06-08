<?php

class QM_AdvancedFeedback_Model_Response extends Varien_Object
{
    public function send()
    {
        Zend_Json::$useBuiltinEncoderDecoder = true;
        if ($this->getError()) $this->setR('error');
        else $this->setR('success');
        Mage::app()->getFrontController()->getResponse()->setBody(Zend_Json::encode($this->getData()));
    }
}
