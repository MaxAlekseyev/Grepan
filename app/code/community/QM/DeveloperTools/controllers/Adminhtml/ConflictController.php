<?php
class QM_DeveloperTools_Adminhtml_ConflictController extends Mage_Adminhtml_Controller_Action {

    public function listAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function refreshAction() {
        Mage::helper('qm_devtools/conflict')->refreshList();
        Mage::getSingleton('adminhtml/session')->addSuccess($this->__('List refreshed'));
        $this->_redirect('*/*/list');
    }

    public function uploadAction() {
        $uploader = new Varien_File_Uploader('config_file');
        $uploader->setAllowedExtensions(array('xml'));
        $path = Mage::app()->getConfig()->getTempVarDir() . '/ExtensionConflict/VirtualNamespace/VirtualModule/etc/';
        $uploader->save($path);
        Mage::helper('qm_devtools/conflict')->refreshList();
        Mage::getSingleton('adminhtml/session')->addSuccess($this->__('File Uploaded and List refreshed'));
        $this->_redirect('*/*/list');
    }

    public function deleteVirtualModuleAction() {
        $filePath = Mage::app()->getConfig()->getTempVarDir() . '/ExtensionConflict/VirtualNamespace/VirtualModule/etc/config.xml';
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
        Mage::helper('qm_devtools/conflict')->refreshList();
        Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Virtual Module deleted and List refreshed'));
        $this->_redirect('*/*/list');
    }

    public function displayFixAction() {
        $this->loadLayout();
        $this->renderLayout();
    }
}
