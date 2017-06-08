<?php

class QM_OneclickOrder_Adminhtml_Oneclickorder_OrderController extends QM_OneclickOrder_Controller_Adminhtml_OneclickOrder
{
    protected function _initOrder()
    {
        $orderId  = (int) $this->getRequest()->getParam('id');
        $order    = Mage::getModel('qmoneclickorder/order');
        if ($orderId) {
            $order->load($orderId);
        }
        Mage::register('current_oneclickorder', $order);
        return $order;
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('qmoneclickorder')->__('OneClick Order'))
             ->_title(Mage::helper('qmoneclickorder')->__('Orders'));
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    public function editAction()
    {
        $orderId    = $this->getRequest()->getParam('id');
        $order      = $this->_initOrder();
        if ($orderId && !$order->getId()) {
            $this->_getSession()->addError(Mage::helper('qmoneclickorder')->__('This order no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getOrderData(true);
        if (!empty($data)) {
            $order->setData($data);
        }
        Mage::register('order_data', $order);
        $this->loadLayout();
        $this->_title(Mage::helper('qmoneclickorder')->__('OneClick Order'))
             ->_title(Mage::helper('qmoneclickorder')->__('Orders'));
        if ($order->getId()){
            $this->_title('#'. $order->getId());
        }
        else{
            $this->_title(Mage::helper('qmoneclickorder')->__('Add order'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    public function newAction()
    {
        //$this->_forward('edit');
        $this->_redirect('*/*/');
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('order')) {
            try {
                $order = $this->_initOrder();
                $order->addData($data);
                $order->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('qmoneclickorder')->__('Order was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $order->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setOrderData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
            catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmoneclickorder')->__('There was a problem saving the order.'));
                Mage::getSingleton('adminhtml/session')->setOrderData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmoneclickorder')->__('Unable to find order to save.'));
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0) {
            try {
                $order = Mage::getModel('qmoneclickorder/order');
                $order->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('qmoneclickorder')->__('Order was successfully deleted.'));
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmoneclickorder')->__('There was an error deleting order.'));
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmoneclickorder')->__('Could not find order to delete.'));
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $orderIds = $this->getRequest()->getParam('order');
        if(!is_array($orderIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmoneclickorder')->__('Please select orders to delete.'));
        }
        else {
            try {
                foreach ($orderIds as $orderId) {
                    $order = Mage::getModel('qmoneclickorder/order');
                    $order->setId($orderId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('qmoneclickorder')->__('Total of %d orders were successfully deleted.', count($orderIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmoneclickorder')->__('There was an error deleting orders.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $orderIds = $this->getRequest()->getParam('order');
        if(!is_array($orderIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmoneclickorder')->__('Please select orders.'));
        }
        else {
            try {
                foreach ($orderIds as $orderId) {
                $order = Mage::getSingleton('qmoneclickorder/order')->load($orderId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d orders were successfully updated.', count($orderIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qmoneclickorder')->__('There was an error updating orders.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction()
    {
        $fileName   = 'order.csv';
        $content    = $this->getLayout()->createBlock('qmoneclickorder/adminhtml_order_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportExcelAction()
    {
        $fileName   = 'order.xls';
        $content    = $this->getLayout()->createBlock('qmoneclickorder/adminhtml_order_grid')->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'order.xml';
        $content    = $this->getLayout()->createBlock('qmoneclickorder/adminhtml_order_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/qmoneclickorder');
    }
}
