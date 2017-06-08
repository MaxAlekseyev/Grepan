<?php

class QM_AdvancedFeedback_ConsultationController extends Mage_Core_Controller_Front_Action
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

        if ($data = $this->getRequest()->getPost('consultation')) {
            $rawData = $data;
            $data = $botHelper->decodeData($data, 'qmadvancedfeedback:consultation');
            $errors = array();

            if (!Zend_Validate::is(trim($data['name']) , 'NotEmpty')) {
                $errors[] = $helper->__('The name cannot be empty.');
            }

            if (isset($data['email']) && !Zend_Validate::is($data['email'], 'EmailAddress')) {
                $errors[] = $helper->__('Invalid email address "%s".', $data['email']);
            }

            if (isset($data['telephone']) && !preg_match($helper->getPhoneValidationPattern(), $data['telephone'])) {
                $errors[] = $helper->__('Invalid telephone number "%s".', $data['telephone']);
            }

            if (isset($data['emailorphone'])) {
                $data['emailorphone'] = trim($data['emailorphone']);
                if (!Zend_Validate::is($data['emailorphone'] , 'NotEmpty')) {
                    $errors[] = $helper->__('The contact field cannot be empty.');
                } else {
                    if (Zend_Validate::is($data['emailorphone'], 'EmailAddress')) {
                        $data['email'] = $data['emailorphone'];
                    } else if(preg_match($helper->getPhoneValidationPattern(), $data['emailorphone'])) {
                        $data['telephone'] = $data['emailorphone'];
                    } else {
                        $errors[] = $helper->__('Invalid contact field data "%s".', $data['emailorphone']);
                    }
                    unset($data['emailorphone']);
                }
            }

            if (count($errors)) {
                if ($isAjax) {
                    $response->setError(implode('<br>', $errors));
                } else {
                    $this->_getSession()->addError(implode('<br>', $errors));
                }
            }

            try {
                $consultation = Mage::getModel('qmadvfeedback/consultation');
                if ($botHelper->checkIsBot($rawData, 'qmadvancedfeedback:consultation')) {
                    $consultation->setSpam(true);
                } else {
                    $consultation->setSpam(false);
                }
                $consultation->addData($data)
                    ->setPageUrl($referer)
                    ->setStatus('new')
                    ->save()
                ;
                $hlp = Mage::helper('qmadvfeedback/consultation');
                $hlp->sendNotifications($consultation->getData());
                if ($isAjax) {
                    if ($hlp->canShowConfirmation()) {
                        $response->setConfirmation($hlp->getConfirmationHtml());
                    }
                    $response->setCustomer($data);
                } else {
                    $this->_getSession()->addSuccess($helper->__('Consultation was successfully received.'));
                }
            }
            catch (Exception $e) {
                Mage::logException($e);
                if ($isAjax) {
                    $response->setError($helper->__('There was a problem saving the consultation.'));
                } else {
                    $this->_getSession()->addError($helper->__('There was a problem saving the consultation.'));
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
