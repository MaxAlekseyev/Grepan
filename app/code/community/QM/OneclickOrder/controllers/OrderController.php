<?php

class QM_OneclickOrder_OrderController extends Mage_Core_Controller_Front_Action
{
    protected function _getSession()
    {
        return Mage::getSingleton('core/session');
    }

    protected function _initProduct(&$orderData)
    {
        if ($productId = $orderData->getProductId()) {
            $product = Mage::getModel('catalog/product')->load($productId);
            if ($product->getId()) {
                $orderData->setProductName($product->getName())
                    ->setProductSku($product->getSku())
                    ->setProductPrice($product->getFinalPrice());
            }
            $orderData->unsProductId();
            Mage::register("qmadvfeedback_current_product", $product);
        }
    }

    protected function _sendNotification($order)
    {
        $helper = Mage::helper('qmoneclickorder/order');
        $addresses = $helper->getEmailNotificationAddressList();

        if (!count($addresses)) {
            return false;
        }

        $sender = array(
            'name' => Mage::getStoreConfig('trans_email/ident_general/name'),
            'email' => Mage::getStoreConfig('trans_email/ident_general/email')
        );
        $storeId = Mage::app()->getStore()->getId();
        $template = $helper->getEmailNotificationTemplate();

        /** Order vars */
        if ($order->getProductPrice()) {
            $order->setProductPrice(Mage::helper('core')->currency($order->getProductPrice(), true, false));
        }
        $order->setBackendUrl(Mage::helper("adminhtml")->getUrl("adminhtml/oneclickorder_order/edit/", array("id" => $order->getId())));

        /** Product vars */
        $currentProduct = Mage::registry("qmadvfeedback_current_product");
        $product = new Varien_Object(array(
            'frontend_url' => $currentProduct->getProductUrl(),
            'backend_url' =>Mage::helper("adminhtml")->getUrl("adminhtml/catalog_product/edit/", array("id" => $currentProduct->getId()))
        ));

        foreach ($addresses as $email) {
            Mage::getModel('core/email_template')->sendTransactional(
                $template, $sender, $email, '', array('order' => $order, 'product' => $product), $storeId
            );
        }

        return true;
    }

    public function addAction()
    {
        $helper = Mage::helper('qmoneclickorder');
        $request = $this->getRequest();
        $isAjax = $request->getParam('isAjax');
        $referer = $this->_getRefererUrl();

        if ($isAjax) {
            $response = Mage::getModel('qmoneclickorder/response');
        }

        if ($orderData = $request->getPost('order')) {
            try {
                $orderData = new Varien_Object($orderData);
                $this->_initProduct($orderData);
                $order = Mage::getModel('qmoneclickorder/order')
                    ->addData($orderData->getData())
                    ->setStatus('new')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->save()
                ;
                $this->_sendNotification($order);
                $helper = Mage::helper('qmoneclickorder/order');
                if ($isAjax) {
                    if ($helper->canShowConfirmation()) {
                        $response->setConfirmation($helper->getConfirmationHtml());
                    }
                    $response->setCustomer($order->getData());
                } else {
                    $this->_getSession()->addSuccess($helper->__('Request was successfully received.'));
                }
            }
            catch (Exception $e) {
                Mage::logException($e);
                if ($isAjax) {
                    $response->setError($helper->__('There was a problem on sending request.'));
                } else {
                    $this->_getSession()->addError($helper->__('There was a problem on sending request.'));
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
