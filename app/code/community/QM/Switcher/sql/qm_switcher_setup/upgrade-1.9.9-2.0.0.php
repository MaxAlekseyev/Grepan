<?php
/** @var QM_Switcher_Model_Resource_Setup $this */

//set the default configuration attribute for store view scope
$this->updateAttribute(
    'catalog_product',
    QM_Switcher_Helper_Data::DEFAULT_CONFIGURATION_ATTRIBUTE_CODE,
    'is_global',
    Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE
);
//fix typos
$paths = array(
    'qm_switcher/settings/change_image_attribtues' => 'qm_switcher/settings/change_image_attributes',
    'qm_switcher/settings/change_media_attribtues' => 'qm_switcher/settings/change_media_attributes'
);
$configTable = $this->getTable('core/config_data');
foreach ($paths as $oldPath => $newPath) {
    $q = "UPDATE `{$configTable}` SET `path` = '".$newPath."' WHERE `path` = '".$oldPath."'";
    $this->run($q);
}
