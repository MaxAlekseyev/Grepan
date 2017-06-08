<?php
 
class QM_DeveloperTools_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getStoreMaintenanceConfig($key, $storeId = null) {
        $path = 'devtools/storemaintenance/' . $key;
        return Mage::getStoreConfig($path, $storeId);
    }

    function isRequestAllowed() {
        $isDebugEnable = Mage::getStoreConfig('devtools/developertoolbar/enabled');
        $clientIp = $this->_getRequest()->getClientIp();
        $allow = false;

        if($isDebugEnable){
            $allow = true;
            $allowedIps = Mage::getStoreConfig('dev/restrict/allow_ips');
            if ( $isDebugEnable && !empty($allowedIps) && !empty($clientIp)) {
                $allowedIps = preg_split('#\s*,\s*#', $allowedIps, null, PREG_SPLIT_NO_EMPTY);
                if (array_search($clientIp, $allowedIps) === false
                    && array_search(Mage::helper('core/http')->getHttpHost(), $allowedIps) === false) {
                    $allow = false;
                }
            }
        }

        return $allow;
    }
}