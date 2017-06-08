<?php

$installer = $this;

$tableName = $installer->getTable('qm_metatager/product_metatag');

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($tableName)
    ->addColumn(
        'product_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
        ),
        'Product ID'
    )
    ->addColumn(
        'store_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
        ),
        'Store ID'
    )
    ->addColumn(
        'meta_title',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable' => false,
        ),
        'Meta Title'
    )
    ->addColumn(
        'meta_keyword',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable' => false,
        ),
        'Meta Keyword'
    )
    ->addColumn(
        'meta_description',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable' => false,
        ),
        'Meta Description'
    )
    ->addIndex(
        $this->getIdxName(
            'qm_metatager/product_metatag',
            array('product_id')
        ),
        array('product_id')
    )
    ->addIndex(
        $this->getIdxName(
            'qm_metatager/product_metatag',
            array('store_id')
        ),
        array('store_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'qm_metatager/product_metatag',
            'product_id',
            'catalog/product',
            'entity_id'
        ),
        'product_id',
        $this->getTable('catalog/product'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'qm_metatager/product_metatag',
            'store_id',
            'core/store',
            'store_id'
        ),
        'store_id',
        $this->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Product Metatags Table');

$installer->getConnection()->createTable($table);

//migrate from metatag module
$metatagTableName = 'qm_product_metatags_entity';
if ($installer->getConnection()->isTableExists($metatagTableName)) {
    $installer->run("INSERT INTO $tableName (product_id, store_id, meta_title, meta_keyword, meta_description)
        SELECT product_id, store_id, meta_title, meta_keyword, meta_description FROM $metatagTableName");
}
//end migrate

$installer->endSetup();