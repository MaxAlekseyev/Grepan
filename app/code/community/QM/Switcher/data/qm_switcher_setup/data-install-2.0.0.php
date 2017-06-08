<?php

/**
 * change callbacks for 1.9 version
 */
if (is_callable(array('Mage', 'getEdition'))) {
    $isEE = Mage::getEdition() == Mage::EDITION_ENTERPRISE;
} else {
    $isEE = Mage::helper('core')->isModuleEnabled('Enterprise_Enterprise');
}
$checkVersion = ($isEE)? '1.14': '1.9';

if (version_compare(Mage::getVersion(), $checkVersion, '>=')) {
    $configSettings = array(
        'qm_switcher/settings/image_change_callback' => 'ProductMediaManager.destroyZoom();
ProductMediaManager.createZoom(jQuery(\'#image-main\'));',
        'qm_switcher/settings/image_selector' => '$(\'image-main\')',
        'qm_switcher/settings/media_change_callback' => 'ProductMediaManager.destroyZoom();
ProductMediaManager.createZoom(jQuery(\'#image-main\'));ProductMediaManager.init();',
        'qm_switcher/settings/media_selector' => '$$(\'.product-view .product-img-box\')[0]',
    );
    foreach ($configSettings as $path=>$value) {
        /** @var Mage_Core_Model_Config_Data $config */
        $config = Mage::getModel('core/config_data')->load($path, 'path');
        $config->setValue($value)->setPath($path);
        $config->save();
    }
}
