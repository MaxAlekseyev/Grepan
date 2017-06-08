<?php

class QM_Switcher_Block_Adminhtml_System_Head extends Mage_Adminhtml_Block_Template
{
    /**
     * @return $this
     */
    public function addResourcesToParent()
    {
        $section = Mage::app()->getRequest()->getParam('section');
        if ($section == 'qm_switcher') {
            /** @var Mage_Adminhtml_Block_Page_Head $head */
            $head = $this->getParentBlock();
            if ($head && $head instanceof Mage_Adminhtml_Block_Page_Head) {
                $head->addJs('qm_switcher/adminhtml/system.js');
            }
        }
        return $this;
    }
}
