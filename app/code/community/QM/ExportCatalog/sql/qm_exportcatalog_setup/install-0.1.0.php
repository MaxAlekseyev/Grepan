<?php

$installer       = $this;
$tableConfigName = $installer->getTable('qm_exportcatalog/table_config');

$installer->startSetup();

$installer->getConnection()->dropTable($tableConfigName);

$tableConfig = $installer->getConnection()
    ->newTable($tableConfigName)
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ))
    ->addColumn('export_catalogs', Varien_Db_Ddl_Table::TYPE_TEXT, '1024', array(
        'nullable' => false,
    ))
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
        'nullable' => false,
    ))
    ->addColumn('file_name', Varien_Db_Ddl_Table::TYPE_TEXT, '255', array(
        'nullable' => false,
    ))
    ->addColumn('add_not_in_stock', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
    ))
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
    ))
    ->addColumn('min_qty', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ))
    ->addColumn('min_price', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ))
    ->addColumn('max_price', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ))
    ->addColumn('bid', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ))
    ->addColumn('cbid', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ));
$installer->getConnection()->createTable($tableConfig);

$installer->endSetup();