<?php

class QM_AdvancedFeedback_Adminhtml_Advancedfeedback_ConsultationController extends QM_AdvancedFeedback_Controller_Adminhtml_AdvancedFeedback
{
    protected function _initConsultation()
    {
        $consultationId  = (int) $this->getRequest()->getParam('id');
        $consultation    = Mage::getModel('qmadvfeedback/consultation');
        if ($consultationId) {
            $consultation->load($consultationId);
        }
        Mage::register('current_consultation', $consultation);
        return $consultation;
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('qmadvfeedback')->__('Advanced Feedback'))
             ->_title(Mage::helper('qmadvfeedback')->__('Сonsultations'));
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    public function editAction()
    {
        $consultationId    = $this->getRequest()->getParam('id');
        $consultation      = $this->_initConsultation();
        if ($consultationId && !$consultation->getId()) {
            $this->_getSession()->addError(Mage::helper('qmadvfeedback')->__('This Сonsultation no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getConsultationData(true);
        if (!empty($data)) {
            $consultation->setData($data);
        }
        Mage::register('consultation_data', $consultation);
        $this->loadLayout();
        $this->_title(Mage::helper('qmadvfeedback')->__('Advanced Feedback'))
             ->_title(Mage::helper('qmadvfeedback')->__('Сonsultations'));
        if ($consultation->getId()){
            $this->_title($consultation->getName());
        }
        else{
            $this->_title(Mage::helper('qmadvfeedback')->__('Add Сonsultation'));
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
        if ($data = $this->getRequest()->getPost('consultation')) {
            try {
                $consultation = $this->_initConsultation();
                $consultation->addData($data);
                $consultation->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('qmadvfeedback')->__('Сonsultation was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $consultation->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setConsultationData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
            catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmadvfeedback')->__('There was a problem saving the Сonsultation.'));
                Mage::getSingleton('adminhtml/session')->setConsultationData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmadvfeedback')->__('Unable to find Сonsultation to save.'));
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0) {
            try {
                $consultation = Mage::getModel('qmadvfeedback/consultation');
                $consultation->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('qmadvfeedback')->__('Сonsultation was successfully deleted.'));
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmadvfeedback')->__('There was an error deleting Сonsultation.'));
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmadvfeedback')->__('Could not find Сonsultation to delete.'));
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $consultationIds = $this->getRequest()->getParam('consultation');
        if(!is_array($consultationIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmadvfeedback')->__('Please select Сonsultations to delete.'));
        }
        else {
            try {
                foreach ($consultationIds as $consultationId) {
                    $consultation = Mage::getModel('qmadvfeedback/consultation');
                    $consultation->setId($consultationId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('qmadvfeedback')->__('Total of %d Сonsultations were successfully deleted.', count($consultationIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmadvfeedback')->__('There was an error deleting Сonsultations.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $consultationIds = $this->getRequest()->getParam('consultation');
        if(!is_array($consultationIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmadvfeedback')->__('Please select Сonsultations.'));
        }
        else {
            try {
                foreach ($consultationIds as $consultationId) {
                $consultation = Mage::getSingleton('qmadvfeedback/consultation')->load($consultationId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d Сonsultations were successfully updated.', count($consultationIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmadvfeedback')->__('There was an error updating Сonsultations.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction()
    {
        $fileName   = 'consultation.csv';
        $content    = $this->getLayout()->createBlock('qmadvfeedback/adminhtml_consultation_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportExcelAction()
    {
        $fileName   = 'consultation.xls';
        $content    = $this->getLayout()->createBlock('qmadvfeedback/adminhtml_consultation_grid')->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'consultation.xml';
        $content    = $this->getLayout()->createBlock('qmadvfeedback/adminhtml_consultation_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('customer/qmadvfeedback/consultation');
    }
}
