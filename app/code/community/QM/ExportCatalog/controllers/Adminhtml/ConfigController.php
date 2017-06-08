<?php

class QM_ExportCatalog_Adminhtml_ConfigController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('catalog');
        $this->renderLayout();
    }

    public function editAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('catalog');
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('catalog');
        $this->renderLayout();
    }

    public function saveAction()
    {
        $this->_saveModel();

        $this->_successApplyForm(Mage::helper('qm_exportcatalog')->__('Config saved'));

    }

    public function deleteAction()
    {
        $model = $this->_loadModel();

        $model->delete();

        $this->_successApplyForm(Mage::helper('qm_exportcatalog')->__('Config deleted'));
    }

    private function _saveModel()
    {
        $config = $this->_loadModel();

        $config->setName($this->getRequest()->getParam('name'));
        $config->setAddNotInStock((bool)$this->getRequest()->getParam('add_not_in_stock'));
        $config->setIsActive((bool)$this->getRequest()->getParam('is_active'));
        $config->setMinQty((int)$this->getRequest()->getParam('min_qty'));
        $config->setMinPrice((int)$this->getRequest()->getParam('min_price'));
        $config->setMaxPrice((int)$this->getRequest()->getParam('max_price'));
        $config->setBid((int)$this->getRequest()->getParam('bid'));
        $config->setCbid((int)$this->getRequest()->getParam('cbid'));
        $config->setExportCatalogs($this->getRequest()->getParam('export_catalogs'));

        $config->save();

        $config->setFileName($config->getId());

        $config->save();

        if(Mage::helper('qm_exportcatalog')->isExportOnsave()) {
            Mage::helper('qm_exportcatalog/export')
                ->exportToYml(Mage::getModel('qm_exportcatalog/config')
                    ->load($config->getId()));
        }
    }

    private function _loadModel()
    {
        $configId = $this->getRequest()->getParam('id');
        $model    = Mage::getModel('qm_exportcatalog/config');

        if ($configId) {
            return $model->load($configId);
        }

        return $model;
    }

    /**
     * @param $message message
     */
    private function _successApplyForm($message)
    {
        Mage::getSingleton('core/session')->addSuccess($message);
        $this->_redirect('*/*/index');
    }

}