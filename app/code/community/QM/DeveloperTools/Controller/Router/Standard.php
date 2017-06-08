<?php
class QM_DeveloperTools_Controller_Router_Standard extends Mage_Core_Controller_Varien_Router_Standard {

    public function match(Zend_Controller_Request_Http $request) {
        $helper = Mage::helper('qm_devtools');
        $storeCode = $request->getStoreCodeFromPath();

        $enabled = $helper->getStoreMaintenanceConfig('enabled', $storeCode);

        if ($enabled) {

            $allowedIPsString = $helper->getStoreMaintenanceConfig('allowed_ips', $storeCode);

            // remove spaces from string
            $allowedIPsString = preg_replace('/ /', '', $allowedIPsString);

            $allowedIPs = array();

            if ('' !== trim($allowedIPsString)) {
                $allowedIPs = explode(',', $allowedIPsString);
            }

            $currentIP = $_SERVER['REMOTE_ADQM'];

            $allowFrontendForAdmins = $helper->getStoreMaintenanceConfig('allow_frontend_for_admins', $storeCode);

            $adminIp = null;
            if ($allowFrontendForAdmins) {
                Mage::getSingleton('core/session', array('name' => 'adminhtml'));

                $adminSession = Mage::getSingleton('admin/session');
                if ($adminSession->isLoggedIn()) {
                    $adminIp = $adminSession['_session_validator_data']['remote_addr'];
                }
            }

            if ($currentIP === $adminIp) {
                $this->__log('Access granted for admin with IP: ' . $currentIP . ' and store ' . $storeCode, 2, $storeCode);
            } else {
                if (!in_array($currentIP, $allowedIPs)) {
                    $this->__log('Access denied  for IP: ' . $currentIP . ' and store ' . $storeCode, 1, $storeCode);

                    $maintenancePage = trim($helper->getStoreMaintenanceConfig('maintenance_page', $storeCode));
                    if ('' !== $maintenancePage) {

                        Mage::getSingleton('core/session', array('name' => 'front'));

                        $response = $this->getFront()->getResponse();

                        $response->setHeader('HTTP/1.1', '503 Service Temporarily Unavailable');
                        $response->setHeader('Status', '503 Service Temporarily Unavailable');
                        $response->setHeader('Retry-After', '5000');

                        $response->setBody($maintenancePage);
                        $response->sendHeaders();
                        $response->outputBody();
                    }
                    exit();
                } else {
                    $this->__log('Access granted for IP: ' . $currentIP . ' and store ' . $storeCode, 2, $storeCode);
                }
            }
        }

        return parent::match($request);
    }

    private function __log($string, $verbosityLevelRequired = 1, $storeCode = null, $zendLevel = Zend_Log::DEBUG) {
        $helper = Mage::helper('qm_devtools');
        $logFile = trim($helper->getStoreMaintenanceConfig('log_file', $storeCode));
        $logVerbosity = trim($helper->getStoreMaintenanceConfig('log_verbosity', $storeCode));

        if ('' === $logFile) {
            $logFile = 'maintenance.log';
        }

        if ($logVerbosity >= $verbosityLevelRequired) {
            Mage::log($string, $zendLevel, $logFile);
        }
    }
}
