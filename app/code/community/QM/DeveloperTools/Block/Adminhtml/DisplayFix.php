<?php
class QM_DeveloperTools_Block_Adminhtml_DisplayFix extends Mage_Adminhtml_Block_Widget_Form_Container {
    private $_conflict = NULL;

    public function getConflict() {
        if ($this->_conflict == NULL) {
            $ecId            = $this->getRequest()->getParam('ec_id');
            $this->_conflict = Mage::getModel('qm_devtools/conflict')->load($ecId);
        }

        return $this->_conflict;
    }

    public function getBackUrl() {
        return $this->getUrl('*/*/list');
    }
}