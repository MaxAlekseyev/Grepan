<?php

class QM_Core_Model_Observer
{
    public function prepareLayoutBefore(Varien_Event_Observer $observer)
    {
        /* @var $block Mage_Page_Block_Html_Head */
        $block = $observer->getEvent()->getBlock();

        if ("head" == $block->getNameInLayout()) {
            foreach (Mage::helper('qmcore')->getFilesCss() as $file) {
                $block->addCss(Mage::helper('qmcore')->getCssPath($file));
            }
        }

        return $this;
    }

    public function layoutLoadBefore(Varien_Event_Observer $observer)
    {
        if (Mage::helper('qmcore')->isCompareBlockDisabled()) {
            $update = $observer->getEvent()->getLayout()->getUpdate();
            $update->addHandle('qm_core_remove_compare');
        }
    }
}