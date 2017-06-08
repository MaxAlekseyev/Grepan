<?php
require_once 'Mage/Adminhtml/controllers/Sales/Order/ShipmentController.php';

class QM_NewPostV2_Adminhtml_Sales_Order_ShipmentController extends Mage_Adminhtml_Sales_Order_ShipmentController
{
    protected function _saveNewPostWaybill(Mage_Sales_Model_Order_Shipment $shipment)
    {
        $helper = Mage::helper('qm_newpostv2');
        $api = Mage::helper('qm_newpostv2/api');
        $currency = Mage::helper('qm_newpostv2/currency');

        $shipmentData = $this->getRequest()->getPost('shipment');
        $newPostData = isset($shipmentData['new_post']) ? $shipmentData['new_post'] : false;

        if (!$newPostData || !isset($newPostData['create_waybill']) || strtolower($newPostData['create_waybill']) != 'on') {
            return;
        }

        $newPostData = new Varien_Object($newPostData);

        $order = $shipment->getOrder();

        Mage::getModel('qm_newpostv2/waybill')->load($order->getId(), 'order_id')->delete();

        $wayBill = Mage::getModel('qm_newpostv2/waybill');

        $wayBill->setDateTime($newPostData->getDepartureDate());
        $wayBill->setDescription($newPostData->getDescription());

        $recipient = $api->createCounterparty($shipment);
        $wayBill->setRecipient($recipient);
        $wayBill->setRecipientsPhone($helper->getPhoneFromShipment($shipment));
        $wayBill->setCityRecipient($helper->getCityFromShipment($shipment));
        $wayBill->setRecipientAddress($api->createCounterpartyAddress($recipient, $shipment));

        $wayBill->setSendersPhone($helper->getSenderPhone());

        $sender = $api->createStoreCounterparty();
        $wayBill->setSender($sender);
        $wayBill->setSenderAddress($api->createStoreCounterpartyAddress($sender));

        $wayBill->setOrder($order);

        $wayBill->setSeatsAmount($helper->findSeatsAmountFromOrder($order));

        $wayBill->setCost($currency->convertCurrencyOut($order->getGrandTotal() - $order->getShippingAmount()));

        $wayBill->setWeight($helper->getOrderWeight($order));

        $wayBill->setVolume($helper->getOrderVolume($order));

        $wayBill->setPayment($order->getPayment()->getMethodInstance());

        $wayBill->setServiceType($helper->getServiceTypeByOrder($order));

        $wayBillResponse = $api->createWaybill($wayBill);

        $wayBill->setWaybillRef($wayBillResponse->getData('Ref'));
        $wayBill->setWaybillNumber($wayBillResponse->getData('IntDocNumber'));

        $wayBill->save();
    }

    /**
     * Save shipment
     * We can save only new shipment. Existing shipments are not editable
     *
     * @return null
     */
    public function saveAction()
    {
        $data = $this->getRequest()->getPost('shipment');
        if (!empty($data['comment_text'])) {
            Mage::getSingleton('adminhtml/session')->setCommentText($data['comment_text']);
        }

        try {
            $shipment = $this->_initShipment();

            if (!$shipment) {
                $this->_forward('noRoute');
                return;
            }

            $shipment->register();
            $comment = '';
            if (!empty($data['comment_text'])) {
                $shipment->addComment(
                    $data['comment_text'],
                    isset($data['comment_customer_notify']),
                    isset($data['is_visible_on_front'])
                );
                if (isset($data['comment_customer_notify'])) {
                    $comment = $data['comment_text'];
                }
            }

            if (!empty($data['send_email'])) {
                $shipment->setEmailSent(true);
            }

            $shipment->getOrder()->setCustomerNoteNotify(!empty($data['send_email']));
            $responseAjax = new Varien_Object();
            $isNeedCreateLabel = isset($data['create_shipping_label']) && $data['create_shipping_label'];

            if ($isNeedCreateLabel && $this->_createShippingLabel($shipment)) {
                $responseAjax->setOk(true);
            }

            $this->_saveNewPostWaybill($shipment);

            $this->_saveShipment($shipment);

            $shipment->sendEmail(!empty($data['send_email']), $comment);

            $shipmentCreatedMessage = $this->__('The shipment has been created.');
            $labelCreatedMessage    = $this->__('The shipping label has been created.');

            $this->_getSession()->addSuccess($isNeedCreateLabel ? $shipmentCreatedMessage . ' ' . $labelCreatedMessage
                : $shipmentCreatedMessage);
            Mage::getSingleton('adminhtml/session')->getCommentText(true);

        } catch (Mage_Core_Exception $e) {
            if ($isNeedCreateLabel) {
                $responseAjax->setError(true);
                $responseAjax->setMessage($e->getMessage());
            } else {
                $this->_getSession()->addError($e->getMessage());
                $this->_redirect('*/*/new', array('order_id' => $this->getRequest()->getParam('order_id')));
            }
        } catch (Exception $e) {
            Mage::logException($e);
            if ($isNeedCreateLabel) {
                $responseAjax->setError(true);
                $responseAjax->setMessage(
                    Mage::helper('sales')->__('An error occurred while creating shipping label.'));
            } else {
                $this->_getSession()->addError($this->__('Cannot save shipment.'));
                $this->_redirect('*/*/new', array('order_id' => $this->getRequest()->getParam('order_id')));
            }

        }
        if ($isNeedCreateLabel) {
            $this->getResponse()->setBody($responseAjax->toJson());
        } else {
            $this->_redirect('*/sales_order/view', array('order_id' => $shipment->getOrderId()));
        }
    }
}
