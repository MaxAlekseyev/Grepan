<?php

class QM_NewPostV2_Adminhtml_SyncController extends Mage_Adminhtml_Controller_Action
{
    protected $_helper;
    protected $_chain = array('syncCity', 'syncArea', 'syncStreet', 'syncWarehouse');

    public function _construct()
    {
        parent::_construct();

        $this->_helper = Mage::helper('qm_newpostv2');
    }

    function syncAllAction()
    {
        $this->_redirect('*/*/' . $this->_chain[0], array(
            'chain' => true,
            'referer' => Mage::helper('core')->urlEncode($this->_getRefererUrl())
        ));
    }

    function syncCityAction()
    {
        $this->_abstractSyncAction('qm_newpostv2/city');
    }

    function syncAreaAction()
    {
        $this->_abstractSyncAction('qm_newpostv2/area');
    }

    function syncStreetAction()
    {
        $this->_abstractSyncAction('qm_newpostv2/street');
    }

    function syncWarehouseAction()
    {
        $this->_abstractSyncAction('qm_newpostv2/warehouse');
    }

    protected function _abstractSyncAction($modelName)
    {
        $isChain = $this->getRequest()->getParam('chain');
        $referer = Mage::helper('core')->urlDecode($this->getRequest()->getParam('referer'));

        try {
            @set_time_limit(0);
            @ini_set('memory_limit', '1024M');
            Mage::getModel($modelName)->getCollection()->sync();
        } catch (Exception $e) {
            $this->_addError($e->getMessage());

            $this->_redirectReferer();
            if ($isChain) {
                $this->_redirectUrl($referer);
            }

            return;
        }

        $this->_addSuccess();

        if ($isChain) {
            $action = $this->getRequest()->getActionName();
            $index = array_search($action, $this->_chain);

            if ($index + 1 < count($this->_chain)) {
                $this->_redirect('*/*/' . $this->_chain[$index + 1], array(
                    'chain' => true,
                    'referer' => Mage::helper('core')->urlEncode($referer)
                ));
            } else {
                $this->_redirectUrl($referer);
            }
            return;
        }

        $this->_redirectReferer();
    }

    protected function _addError($message)
    {
        Mage::getSingleton('core/session')->addError($message);
    }

    protected function _addSuccess()
    {
        Mage::getSingleton('core/session')->addSuccess($this->_helper->__('Success update'));
    }
} 