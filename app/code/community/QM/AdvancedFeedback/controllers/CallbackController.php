<?php

class QM_AdvancedFeedback_CallbackController extends Mage_Core_Controller_Front_Action
{
    protected function _getSession()
    {
        return Mage::getSingleton('core/session');
    }

    protected function _getRefererUrl()
    {
        $referer = $this->getRequest()->getParam(QM_AdvancedFeedback_Helper_Data::REFERER_QUERY_PARAM_NAME);
        if ($referer) {
            $referer = Mage::getModel('core/url')->getRebuiltUrl(Mage::helper('core')->urlDecode($referer));
            if ($this->_isUrlInternal($referer)) {
                return $referer;
            }
        }
        return Mage::getBaseUrl();
    }

    public function addAction()
    {
        $helper = Mage::helper('qmadvfeedback');
        $botHelper = Mage::helper('qmadvfeedback/botHelper');
        $isAjax = $this->getRequest()->getParam('isAjax');
        $referer = $this->_getRefererUrl();

        if ($isAjax) {
            $response = Mage::getModel('qmadvfeedback/response');
        }

        if ($data = $this->getRequest()->getPost('callback')) {
            try {
                $callback = Mage::getModel('qmadvfeedback/callback');
                if ($botHelper->checkIsBot($data, 'qmadvancedfeedback:callback')) {
                    $callback->setSpam(true);
                } else {
                    $callback->setSpam(false);
                }

                $data = $botHelper->decodeData($data, 'qmadvancedfeedback:callback');
                $callback->addData($data)
                    ->setPageUrl($referer)
                    ->setStatus('new')
                    ->save()
                ;
                $hlp = Mage::helper('qmadvfeedback/callback');
                $hlp->sendNotifications($callback->getData());
                if ($isAjax) {
                    if ($hlp->canShowConfirmation()) {
                        $response->setConfirmation($hlp->getConfirmationHtml());
                    }
                    $response->setCustomer($data);
                } else {
                    $this->_getSession()->addSuccess($helper->__('Callback was successfully received.'));
                }
            }
            catch (Exception $e) {
                Mage::logException($e);
                if ($isAjax) {
                    $response->setError($helper->__('There was a problem saving the callback.'));
                } else {
                    $this->_getSession()->addError($helper->__('There was a problem saving the callback.'));
                }
            }
        }

        if ($isAjax) {
            $response->send();
        } else {
            $this->_redirectUrl($referer);
        }
    }
}
