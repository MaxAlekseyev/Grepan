<?php

class QM_Core_Block_Page_Html_Head extends Mage_Page_Block_Html_Head
{
    protected function _construct()
    {
        parent::_construct();
        if (!Mage::helper('qmcore')->addJsAtEnd()) {
            $this->addQmcoreJs();
        }
    }

    public function getCssJsHtml()
    {
        if (Mage::helper('qmcore')->addJsAtEnd()) {
            $this->addQmcoreJs();
        }
        return parent::getCssJsHtml();
    }

    private function addQmcoreJs()
    {
        foreach (Mage::helper('qmcore')->getFilesJs() as $file) {
            $this->addJs($file);
        }
    }
}
