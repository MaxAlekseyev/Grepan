<?php

class QM_NewPostV2_Helper_Api extends Mage_Core_Helper_Abstract
{
    const NEW_POST_URL = 'https://my.novaposhta.ua/';

    const NEWPOST_API_URL = 'https://api.novaposhta.ua/v2.0/json/';
    const RESPONSE_LOG_FILE_NAME = 'qm_newpostv2_response';

    const WAREHOUSE_WAREHOUSE_RATE_CODE = 'qm_newpostv2_warehouse_warehouse';
    const WAREHOUSE_DOORS_RATE_CODE = 'qm_newpostv2_warehouse_doors';

    const WAREHOUSE_WAREHOUSE_SERVICE_TYPE = 'WarehouseWarehouse';
    const WAREHOUSE_DOORS_SERVICE_TYPE = 'WarehouseDoors';

    const PAYER_TYPE_SENDER = 'Sender';
    const PAYER_TYPE_RECIPIENT = 'Recipient';

    const NEWPOST_MIN_VOLUME = 0.0004;

    protected $_apiKey;
    protected $_currencyHelper;
    protected $_helper;

    public function __construct()
    {
        $this->_helper         = Mage::helper('qm_newpostv2');
        $this->_currencyHelper = Mage::helper('qm_newpostv2/currency');
        $this->_apiKey         = Mage::getStoreConfig('qm_newpostv2/settings/api_key');
    }

    protected function _getQuote()
    {
        $cart = Mage::getSingleton('checkout/cart');
        $quote = Mage::registry('qm_newpostv2_quote_before_collect');

        if (!$quote) {
            $quote = $cart->getQuote();
        }

        return $quote;
    }

    protected function _getCity()
    {
        $city = Mage::getModel('qm_newpostv2/city');

        $city = $city->loadByLocaleDescription($this->_getQuote()->getShippingAddress()->getCity());

        if (!$city->getId()) {
            $lastOrder = Mage::helper('qmcheckout')->getLastCustomerOrder();

            if ($lastOrder) {
                $city = $city->loadByLocaleDescription($lastOrder->getShippingAddress()->getCity());
            }
        }

        if (!$city->getId()) {
            return false;
        }

        return $city;
    }

    public function getShippingPrice($serviceType)
    {
        $city = $this->_getCity();

        if (!$city) {
            return 0;
        }

        $data = array();

        switch ($serviceType) {
            case self::WAREHOUSE_WAREHOUSE_SERVICE_TYPE:
                $data['ServiceType'] = self::WAREHOUSE_WAREHOUSE_SERVICE_TYPE;
                break;
            case self::WAREHOUSE_DOORS_SERVICE_TYPE:
                $data['ServiceType'] = self::WAREHOUSE_DOORS_SERVICE_TYPE;
                break;
            default:
                $this->_helper->logError('Undefined service type for shipping. Type:' . $serviceType);

                return 0;
        }

        $data['CityRecipient'] = $city->getRef();
        $data['CitySender']    = $this->_helper->getCitySender();

        $allItems = $this->_getQuote()->getAllItems();

        return $this->_getProductsShippingPrice($allItems, $data);
    }

    protected function _getProductsShippingPrice($allItems, $data)
    {
        $price              = 0;
        $itemsWithoutVolume = array();

        foreach ($allItems as $item) {
            $product = $item->getProduct();
            $product = $product->load($product->getId());

            $volume = $this->_helper->getProductVolume($product);
            if ($volume >= self::NEWPOST_MIN_VOLUME) {
                $price += $item->getQty() * $this->_getVolumeProductShippingPrice($product, $data);
            } else {
                array_push($itemsWithoutVolume, $item);
            }
        }

        $price += $this->_getWeightItemsShippingPrice($itemsWithoutVolume, $data);

        return $price;
    }

    protected function _getVolumeProductShippingPrice($product, $data)
    {
        $data['VolumeGeneral'] = round(
            ($product->getWidth() * $product->getHeight() * $product->getDepth()) / 1000000
            , 3);
        $data['Weight']        = $product->getWeight();
        $data['Cost']          = $product->getPrice();

        return $this->_getShippingPriceApiCall($data);
    }

    protected function _getWeightItemsShippingPrice($itemsArray, $data)
    {
        if (!count($itemsArray)) {
            return 0;
        }

        $allWeight = 0;
        $allCost   = 0;

        foreach ($itemsArray as $item) {
            $product = $item->getProduct();

            $allCost += $item->getQty() * $product->getPrice();
            $allWeight += $item->getQty() * $product->getWeight();
        }

        $data['Cost']   = $allCost;
        $data['Weight'] = $allWeight;

        return $this->_getShippingPriceApiCall($data);
    }

    protected function _getShippingPriceApiCall($data)
    {
        $data['Cost']     = $this->_currencyHelper->convertCurrencyOut($data['Cost']);
        $data['DateTime'] = Mage::getModel('core/date')->date('d.m.Y');

        $responseData = $this->call('InternetDocument', 'getDocumentPrice', $this->_prepareMethodProperties($data));

        if (isset($responseData[0]['Cost'])) {
            return $this->_currencyHelper->convertCurrencyIn($responseData[0]['Cost']);
        }
    }

    protected function _prepareMethodProperties($props)
    {
        $props['Weight']        = isset($props['Weight']) && $props['Weight'] ? $props['Weight'] : $this->_helper->getDefaultWeight();
        $props['VolumeGeneral'] = isset($props['VolumeGeneral']) && $props['VolumeGeneral'] ? $props['VolumeGeneral'] : $this->_helper->getDefaultVolume();

        return $props;
    }

    public function call($modelName, $calledMethod, $methodProperties = array(), $returnFullRes = false)
    {
        $data = array();

        $data['apiKey']       = $this->_apiKey;
        $data['modelName']    = $modelName;
        $data['calledMethod'] = $calledMethod;

        if ($methodProperties) {
        	$data['methodProperties'] = $methodProperties;
        }

        $res = $this->_sendJson(Mage::helper('core')->jsonEncode($data));

        return $returnFullRes ? $res : $res['data'];
    }

    protected function _sendJson($json = '')
    {
        $response = Mage::helper('core')->jsonDecode($this->_sendRequest($json), true);

        $this->_logResponse($response, $json);

        return $response;
    }

    protected function _sendRequest($body = '')
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, self::NEWPOST_API_URL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/json"));
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        } catch (Exception $e) {
            $this->_helper->logError('Curl error ' . $e->getMessage());
        }
    }

    protected function _logResponse($response, $json)
    {
        if (!$this->_helper->isLogEnable()) {
            return;
        }
        //if ($response['errors'] || $response['warnings'] || $response['info']) {
        Mage::log($this->__('For data: ') . $json, 0, self::RESPONSE_LOG_FILE_NAME);
        Mage::log($this->__('Response: ') . Mage::helper('core')->jsonEncode($response, JSON_FORCE_OBJECT), 0, self::RESPONSE_LOG_FILE_NAME);
        //}

        if ($response['errors']) {
            if (is_array($response['errors'])) {
                foreach ($response['errors'] as $error) {
                    Mage::log($error, 3, self::RESPONSE_LOG_FILE_NAME);
                }
            } else {
                Mage::log($response['errors'], 3, self::RESPONSE_LOG_FILE_NAME);
            }
        }

        if ($response['warnings']) {
            if (is_array($response['warnings'])) {
                foreach ($response['warnings'] as $warning) {
                    Mage::log($warning, 4, self::RESPONSE_LOG_FILE_NAME);
                }
            } else {
                Mage::log($response['warnings'], 4, self::RESPONSE_LOG_FILE_NAME);
            }
        }

        if ($response['info']) {
            if (is_array($response['info'])) {
                foreach ($response['info'] as $info) {
                    Mage::log($info, 0, self::RESPONSE_LOG_FILE_NAME);
                }
            } else {
                Mage::log($response['info'], 0, self::RESPONSE_LOG_FILE_NAME);
            }
        }
    }

    protected function _handleUserResponseErrors($res)
    {
        if (!$res['errors']) {
            return;
        }
        $msg = '';

        foreach ($res['errors'] as $key => $err) {
            $m = $err ? $err : $key;
            $msg .= $this->_helper->__($m) . ' for New Post. ';
        }

        Mage::throwException($msg);
    }

    public function createCounterparty(Mage_Sales_Model_Order_Shipment $shipment)
    {
        $data    = array();
        $address = $this->_helper->getAddressFromShipment($shipment);

        if (!$data['CityRef'] = $this->_helper->getCityFromShipment($shipment)) {
            Mage::throwException($this->_helper->__('Wrong or Empty City for New Post'));
        }

        if (!$data['FirstName'] = $address->getFirstname()) {
            Mage::throwException($this->_helper->__('Empty First Name for New Post'));
        }

        if (!$data['LastName'] = $address->getLastname()) {
            Mage::throwException($this->_helper->__('Empty Last Name for New Post'));
        }

        if ($this->_helper->isForceTranslitToRus()) {
            $data['FirstName'] = $this->_helper->translitToRu($data['FirstName']);
            $data['LastName']  = $this->_helper->translitToRu($data['LastName']);
        }

        if (!$data['Phone'] = $this->_helper->getPhoneFromShipment($shipment)) {
            Mage::throwException($this->_helper->__('Empty Telephone for New Post'));
        }

        $data['CounterpartyType']     = 'PrivatePerson';
        $data['CounterpartyProperty'] = 'Recipient';

        $res = $this->call('Counterparty', 'save', $data, true);

        $this->_handleUserResponseErrors($res);

        return new Varien_Object($res['data'][0]);
    }

    public function createStoreCounterparty()
    {
        $data    = array();

        $data['CityRef'] = $this->_helper->getCitySender();

        $res = $this->call('Counterparty', 'cloneLoyaltyCounterpartySender', $data, true);

        $this->_handleUserResponseErrors($res);

        $data['CounterpartyProperty'] = 'Sender';

        $res = $this->call('Counterparty', 'getCounterparties', $data, true);

        $this->_handleUserResponseErrors($res);

        $sender = new Varien_Object($res['data'][0]);

        $res = $this->call('Counterparty', 'getCounterpartyContactPersons', array('Ref'=>$sender->getData('Ref')), true);

        $this->_handleUserResponseErrors($res);

        $sender->setData('ContactPerson', array('data' => $res['data']));

        return $sender;
    }

    public function createStoreCounterpartyAddress(Varien_Object $counterparty)
    {
        $data                    = array();
        $data['CounterpartyRef'] = $counterparty->getData('Ref');

        $warehouse = Mage::getModel('qm_newpostv2/warehouse')->loadByRef($this->_helper->getWarehouseSender());

        $data['StreetRef']       = $this->_getStreetFromWarehouse($warehouse);
        $data['BuildingNumber']  = $this->_getBuildingFromWarehouse($warehouse);

        $res = $this->call('Address', 'save', $data, true);

        $this->_handleUserResponseErrors($res);

        return new Varien_Object($res['data'][0]);
    }

    protected function _getUkrainianAddressFromWarehouse($address)
    {
        return Mage::getSingleton('qm_newpostv2/warehouse')->loadByLocaleDescription($address)->getDescription();
    }

    protected function _getStreetFromWarehouse(QM_NewPostV2_Model_Warehouse $warehouse)
    {
        $streetRaw = $warehouse->getDescription();//in streets supports only ukrainian

        $streetRaw = preg_replace('/^.*\: */U', '', $streetRaw);

        preg_match('/^(.*\.)/U', $streetRaw, $regRes);
        $streetType = trim($regRes[1]);
        $streetRaw = preg_replace('/^(.*\.)/U', '', $streetRaw);


        preg_match('/^(.*)(\,.*)?$/U', $streetRaw, $regRes);
        $streetDescr = trim($regRes[1]);

        return Mage::getSingleton('qm_newpostv2/street')->getCollection()->addFilter('description', $streetDescr)
            ->addFilter('streets_type', $streetType)->getFirstItem()->getRef();
    }

    protected function _getBuildingFromWarehouse(QM_NewPostV2_Model_Warehouse $warehouse)
    {
        $address = $warehouse->getDescription();

        preg_match('/\, *(.*)$/', $address, $regRes);

        return $regRes[1];
    }

    protected function _getStreetFromShipment(Mage_Sales_Model_Order_Shipment $shipment)
    {
        $address   = $this->_helper->getAddressFromShipment($shipment);
        $shippingMethod = $shipment->getOrder()->getShippingMethod();

        if ($this->_helper->isWarehouseToWarehouseService($shippingMethod)) {
            $warehouse = Mage::getSingleton('qm_newpostv2/warehouse')->loadByLocaleDescription($address->getStreet()[0]);

            return $this->_getStreetFromWarehouse($warehouse);
        } else if ($this->_helper->isDoorsToDoorsService($shippingMethod)) {
            $streetRaw = $address->getStreet()[0];

            preg_match('/^(.*\.)/U', $streetRaw, $regRes);
            $streetType = trim($regRes[1]);
            $streetRaw = preg_replace('/^(.*\.)/U', '', $streetRaw);


            preg_match('/^(.*)(\,.*)?$/U', $streetRaw, $regRes);
            $streetDescr = trim($regRes[1]);

            return Mage::getSingleton('qm_newpostv2/street')->getCollection()->addFilter('description', $streetDescr)
                ->addFilter('streets_type', $streetType)->getFirstItem()->getRef();
        } else {
            throw new Exception('Invalid Shipping Method');
        }

    }

    protected function _getBuildingFromShipment(Mage_Sales_Model_Order_Shipment $shipment)
    {
        $address = $this->_helper->getAddressFromShipment($shipment);
        $shippingMethod = $shipment->getOrder()->getShippingMethod();

        if ($this->_helper->isWarehouseToWarehouseService($shippingMethod)) {
            preg_match('/\, *(.*)$/U', $address->getStreet()[0], $regRes);
        } else if ($this->_helper->isDoorsToDoorsService($shippingMethod)) {
            preg_match('/\, *(.*),.*$/U', $address->getStreet()[0], $regRes);
        } else {
            throw new Exception('Invalid Shipping Method');
        }

        return trim($regRes[1]);
    }

    protected function _getApartmentFromShipment(Mage_Sales_Model_Order_Shipment $shipment)
    {
        $address = $this->_helper->getAddressFromShipment($shipment);

        preg_match('/\.*,.*, *?(.*)$/U', $address->getStreet()[0], $regRes);

        return trim($regRes[1]);
    }

    public function createCounterpartyAddress(Varien_Object $counterparty, Mage_Sales_Model_Order_Shipment $shipment)
    {
        $data                    = array();
        $data['CounterpartyRef'] = $counterparty->getData('Ref');
        $data['StreetRef']       = $this->_getStreetFromShipment($shipment);
        $data['BuildingNumber']  = $this->_getBuildingFromShipment($shipment);

        $shippingMethod = $shipment->getOrder()->getShippingMethod();
        if ($this->_helper->isDoorsToDoorsService($shippingMethod)) {
            $flat = $this->_getApartmentFromShipment($shipment);

            if ($flat) {
                $data['Flat']  = $flat;
            }
        }

        $res = $this->call('Address', 'save', $data, true);

        $this->_handleUserResponseErrors($res);

        return new Varien_Object($res['data'][0]);

    }

    public function createWaybill(QM_NewPostV2_Model_Waybill $waybill)
    {
        $props = $waybill->getRequestData();
        $res   = $this->call('InternetDocument', 'save', $this->_prepareMethodProperties($props), true);

        $this->_handleUserResponseErrors($res);

        return new Varien_Object($res['data'][0]);
    }

    public function getWaybillPrintUrl(QM_NewPostV2_Model_Waybill $waybill)
    {
        $params = array(
            'orders' => 'printDocument',
            'orders[]' => $waybill->getWaybillRef(),
            'type' => 'pdf',
            'apiKey' => $this->_apiKey
        );

        $url = self::NEW_POST_URL;

        foreach ($params as $key => $val) {
            $url .= $key . '/' . $val . '/';
        }

        return $url;
    }

    public function trackWaybill(QM_NewPostV2_Model_Waybill $waybill)
    {
        $data = array();
        $data['Documents'] = array($waybill->getWaybillNumber());

        $res = $this->call('InternetDocument', 'documentsTracking', $data, true);

        $this->_handleUserResponseErrors($res);

        return new Varien_Object($res['data'][0]);
    }
}