<?php

class QM_NewPostV2_Helper_Data extends Mage_Core_Helper_Abstract
{
    const LOG_FILE_NAME = 'qm_newpostv2_log';
    const RU_LOCALE = 'ru';
    const UA_LOCALE = 'ua';
    protected $_translit;

    public function __construct()
    {
        $this->_translit = array(
            'en' => array('a', 'b', 'v', 'g', 'd', 'e', 'yo', 'zh', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'x', 'c', 'ch', 'sh', 'shh', '\'', 'y', '\'\'', 'e\'', 'yu', 'ya', 'A', 'B', 'V', 'G', 'D', 'E', 'YO', 'Zh', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'X', 'C', 'CH', 'SH', 'SHH', '\'', 'Y\'', '\'\'', 'E\'', 'YU', 'YA'),
            'ru' => array('а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ь', 'ы', 'ъ', 'э', 'ю', 'я', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ь', 'Ы', 'Ъ', 'Э', 'Ю', 'Я')
        );
    }

    public function getCitySender()
    {
        $cityRef = Mage::getStoreConfig('qm_newpostv2/settings/city_sender');

        if ($cityRef) {
            return $cityRef;
        }

        $this->logError('Empty city sender setting');
    }

    public function getWarehouseSender()
    {
        $warehouseRef = Mage::getStoreConfig('qm_newpostv2/settings/warehouse_sender');

        if ($warehouseRef) {
            return $warehouseRef;
        }

        $this->logError('Empty warehouse sender setting');
    }

    public function getLocale()
    {
        return Mage::getStoreConfig('qm_newpostv2/settings/locale');
    }

    public function isLogEnable()
    {
        return Mage::getStoreConfig('qm_newpostv2/settings/enable_log');
    }

    public function getDefaultVolume()
    {
        $val = floatval(Mage::getStoreConfig('qm_newpostv2/settings/default_volume'));

        return $val ? $val : 0.0001;
    }

    public function getDefaultWeight()
    {
        $val = floatval(Mage::getStoreConfig('qm_newpostv2/settings/default_weight'));

        return $val ? $val : 0.0001;
    }

    public function log($message)
    {
        if ($this->isLogEnable()) {
            Mage::log($message, null, self::LOG_FILE_NAME);
        }
    }

    public function logError($message)
    {
        Mage::log($message, 3, self::LOG_FILE_NAME);
    }

    public function isForceTranslitToRus()
    {
        return Mage::getStoreConfig('qm_newpostv2/settings/force_translit');
    }

    public function isUseStorePhoneIfRecipientPhoneDoesntExist()
    {
        return Mage::getStoreConfig('qm_newpostv2/settings/use_store_phone_if_recipient_dont_exist');
    }

    protected function _formatPhone($phone)
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }

    public function getSenderPhone()
    {
        return $this->_formatPhone(Mage::getStoreConfig('qm_newpostv2/settings/sender_phone'));
    }

    public function isSenderBackwardDeliveryPayer()
    {
        return Mage::getStoreConfig('qm_newpostv2/settings/sender_is_backward_delivery_payer');
    }

    public function createWaybillByDefault()
    {
        return Mage::getStoreConfig('qm_newpostv2/settings/create_waybill_by_default');
    }
    public function getCityFromShipment(Mage_Sales_Model_Order_Shipment $shipment)
    {
        $address = $shipment->getOrder()->getShippingAddress();
        $address = $address->load($address->getId());

        $cityName = $address->getCity();
        $city = Mage::getSingleton('qm_newpostv2/city')->loadByLocaleDescription($cityName)->getRef();

        return $city;
    }

    public function getAddressFromShipment(Mage_Sales_Model_Order_Shipment $shipment)
    {
        $address = $shipment->getOrder()->getShippingAddress();
        return $address->load($address->getId());
    }


    public function getPhoneFromShipment(Mage_Sales_Model_Order_Shipment $shipment)
    {
        $address = $shipment->getOrder()->getShippingAddress();
        $address = $address->load($address->getId());

        $phone = $this->_formatPhone($address->getTelephone());

        if (!$phone && $this->isUseStorePhoneIfRecipientPhoneDoesntExist()) {
            $phone = $this->getSenderPhone();
        }

        if (!$phone) {
            return false;
        }

        return $phone;
    }

    public function translitToRu($text)
    {
        $translited = '';

        $length = strlen($text);

        for ($i = 0; $i < $length; $i++) {
            $trIndex = array_search($text[$i], $this->_translit['en']);

            $translited .= isset($this->_translit['ru'][$trIndex]) ? $this->_translit['ru'][$trIndex] : $text[$i];
        }

        return $translited;
    }

    public function getProductVolume(Mage_Catalog_Model_Product $product)
    {
        return $product->getWidth() * $product->getHeight() * $product->getDepth();
    }

    public function getOrderWeight(Mage_Sales_Model_Order $order){
        $weight = 0;
        foreach($order->getAllItems() as $item) {
            $weight += $item->getWeight() * $item->getQtyOrdered() ;
        }

        return $weight;
    }

    public function getOrderVolume(Mage_Sales_Model_Order $order)
    {
        $volume = 0;
        foreach ($order->getAllItems() as $item) {
            $volume += $this->getProductVolume($item->getProduct()) * $item->getQtyOrdered();
        }

        return $volume;
    }

    public function getServiceTypeByOrder(Mage_Sales_Model_Order $order)
    {
        switch ($order->getShippingMethod()) {
            case (QM_NewPostV2_Helper_Api::WAREHOUSE_WAREHOUSE_RATE_CODE . '_' . QM_NewPostV2_Helper_Api::WAREHOUSE_WAREHOUSE_RATE_CODE) :
                return QM_NewPostV2_Helper_Api::WAREHOUSE_WAREHOUSE_SERVICE_TYPE;
                break;
            case (QM_NewPostV2_Helper_Api::WAREHOUSE_DOORS_RATE_CODE . '_' . QM_NewPostV2_Helper_Api::WAREHOUSE_DOORS_RATE_CODE) :
                return QM_NewPostV2_Helper_Api::WAREHOUSE_DOORS_SERVICE_TYPE;
                break;
            default:
                throw new Exception('Invalid Shipping Method');
        }
    }

    public function isWarehouseToWarehouseService($shippingMethod)
    {
        return $shippingMethod == QM_NewPostV2_Helper_Api::WAREHOUSE_WAREHOUSE_RATE_CODE . '_' . QM_NewPostV2_Helper_Api::WAREHOUSE_WAREHOUSE_RATE_CODE;
    }

    public function isDoorsToDoorsService($shippingMethod)
    {
        return $shippingMethod == QM_NewPostV2_Helper_Api::WAREHOUSE_DOORS_RATE_CODE . '_' . QM_NewPostV2_Helper_Api::WAREHOUSE_DOORS_RATE_CODE;
    }

    public function findSeatsAmountFromOrder(Mage_Sales_Model_Order $order)
    {
        $withoutVolumeProductSeats = 0;
        $seats = 0;
        //each product with volume get one seat, all products without volume get one seat
        foreach ($order->getAllItems() as $item) {
            if ($this->getProductVolume($item->getProduct())) {
                $seats += $item->getQtyOrdered();
            } else {
                $withoutVolumeProductSeats = 1;
            }
        }

        return $seats + $withoutVolumeProductSeats;
    }
}