<?php

$installer = $this;

$installer->startSetup();

$objCatalogEavSetup = Mage::getResourceModel('catalog/eav_mysql4_setup', 'core_setup');
$attrCodesArray     = array('width' => 'Width', 'height' => 'Height', 'depth' => 'Depth');

foreach ($attrCodesArray as $attrCode => $attrLabel) {
    $attrId = $objCatalogEavSetup->getAttributeId(Mage_Catalog_Model_Product::ENTITY, $attrCode);

    if ($attrId === false) {
        $objCatalogEavSetup->addAttribute(Mage_Catalog_Model_Product::ENTITY, $attrCode, array(
            'group'                => 'New Post v2',
            'sort_order'           => 0,
            'type'                 => 'decimal',
            'backend'              => '',
            'frontend'             => '',
            'label'                => $attrLabel,
            'note'                 => '',
            'input'                => 'text',
            'class'                => '',
            'source'               => '',
            'global'               => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
            'visible'              => true,
            'required'             => false,
            'user_defined'         => false,
            'default'              => '0',
            'visible_on_front'     => false,
            'unique'               => false,
            'is_configurable'      => false,
            'used_for_promo_rules' => true
        ));
    }
}

///////////////Table city/////////////////////
$tableCityName = $installer->getTable('qm_newpostv2/table_city');

$installer->getConnection()->dropTable($tableCityName);

$tableCity = $installer->getConnection()
    ->newTable($tableCityName)
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
    ))
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => true,
    ))
    ->addColumn('description_ru', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => true,
    ))
    ->addColumn('ref', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => false,
    ))
    ->addColumn('area', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => false,
    ));

for ($day = 1; $day <= 7; $day++) {
    $tableCity->addColumn('Delivery' . $day, Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
    ));
}

$installer->getConnection()->createTable($tableCity);

///////////////Table area/////////////////////
$tableAreaName = $installer->getTable('qm_newpostv2/table_area');

$installer->getConnection()->dropTable($tableAreaName);

$tableArea = $installer->getConnection()
    ->newTable($tableAreaName)
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
    ))
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => true,
    ))
    ->addColumn('ref', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => false,
    ))
    ->addColumn('areas_center', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => true,
    ));

$installer->getConnection()->createTable($tableArea);

///////////////Table street/////////////////////
$tableStreetName = $installer->getTable('qm_newpostv2/table_street');

$installer->getConnection()->dropTable($tableStreetName);

$tableStreet = $installer->getConnection()
    ->newTable($tableStreetName)
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
    ))
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => true,
    ))
    ->addColumn('city_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => false,
    ))
    ->addColumn('ref', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => false,
    ))
    ->addColumn('streets_type_ref', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => false,
    ))
    ->addColumn('streets_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => true,
    ));

$installer->getConnection()->createTable($tableStreet);

///////////////Table warehouse/////////////////////
$tableWarehouseName = $installer->getTable('qm_newpostv2/table_warehouse');

$installer->getConnection()->dropTable($tableWarehouseName);

$tableWarehouse = $installer->getConnection()
    ->newTable($tableWarehouseName)
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
    ))
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => true,
    ))
    ->addColumn('description_ru', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => true,
    ))
    ->addColumn('ref', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => false,
    ))
    ->addColumn('city_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => false,
    ))
    ->addColumn('number', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => true,
    ))
    ->addColumn('longitude', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => true,
    ))
    ->addColumn('latitude', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => true,
    ));

$installer->getConnection()->createTable($tableWarehouse);

$installer->endSetup();