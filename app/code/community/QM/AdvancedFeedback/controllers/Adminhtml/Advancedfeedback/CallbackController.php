<?php

class QM_AdvancedFeedback_Adminhtml_Advancedfeedback_CallbackController extends QM_AdvancedFeedback_Controller_Adminhtml_AdvancedFeedback
{
    protected function _initCallback()
    {
        $callbackId  = (int) $this->getRequest()->getParam('id');
        $callback    = Mage::getModel('qmadvfeedback/callback');
        if ($callbackId) {
            $callback->load($callbackId);
        }
        Mage::register('current_callback', $callback);
        return $callback;
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('qmadvfeedback')->__('Advanced Feedback'))
             ->_title(Mage::helper('qmadvfeedback')->__('Callbacks'));
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    public function editAction()
    {
        $callbackId    = $this->getRequest()->getParam('id');
        $callback      = $this->_initCallback();
        if ($callbackId && !$callback->getId()) {
            $this->_getSession()->addError(Mage::helper('qmadvfeedback')->__('This callback no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getCallbackData(true);
        if (!empty($data)) {
            $callback->setData($data);
        }
        Mage::register('callback_data', $callback);
        $this->loadLayout();
        $this->_title(Mage::helper('qmadvfeedback')->__('Advanced Feedback'))
             ->_title(Mage::helper('qmadvfeedback')->__('Callbacks'));
        if ($callback->getId()){
            $this->_title($callback->getName());
        }
        else{
            $this->_title(Mage::helper('qmadvfeedback')->__('Add callback'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('callback')) {
            try {
                $callback = $this->_initCallback();
                $callback->addData($data);
                Mage::log($data);
                $callback->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('qmadvfeedback')->__('Callback was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $callback->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setCallbackData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
            catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmadvfeedback')->__('There was a problem saving the callback.'));
                Mage::getSingleton('adminhtml/session')->setCallbackData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmadvfeedback')->__('Unable to find callback to save.'));
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0) {
            try {
                $callback = Mage::getModel('qmadvfeedback/callback');
                $callback->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('qmadvfeedback')->__('Callback was successfully deleted.'));
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmadvfeedback')->__('There was an error deleting callback.'));
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmadvfeedback')->__('Could not find callback to delete.'));
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $callbackIds = $this->getRequest()->getParam('callback');
        if(!is_array($callbackIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmadvfeedback')->__('Please select callbacks to delete.'));
        }
        else {
            try {
                foreach ($callbackIds as $callbackId) {
                    $callback = Mage::getModel('qmadvfeedback/callback');
                    $callback->setId($callbackId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('qmadvfeedback')->__('Total of %d callbacks were successfully deleted.', count($callbackIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmadvfeedback')->__('There was an error deleting callbacks.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $callbackIds = $this->getRequest()->getParam('callback');
        if(!is_array($callbackIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmadvfeedback')->__('Please select callbacks.'));
        }
        else {
            try {
                foreach ($callbackIds as $callbackId) {
                $callback = Mage::getSingleton('qmadvfeedback/callback')->load($callbackId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d callbacks were successfully updated.', count($callbackIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmadvfeedback')->__('There was an error updating callbacks.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction()
    {
        $fileName   = 'callback.csv';
        $content    = $this->getLayout()->createBlock('qmadvfeedback/adminhtml_callback_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportExcelAction()
    {
        $fileName   = 'callback.xls';
        $content    = $this->getLayout()->createBlock('qmadvfeedback/adminhtml_callback_grid')->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'callback.xml';
        $content    = $this->getLayout()->createBlock('qmadvfeedback/adminhtml_callback_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('customer/qmadvfeedback/callback');
    }
}
