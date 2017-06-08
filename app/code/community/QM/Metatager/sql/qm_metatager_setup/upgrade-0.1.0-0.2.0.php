<?php

$installer = $this;
$tableName = $installer->getTable('qm_metatager/category_metatag');

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($tableName)
    ->addColumn(
        'category_id',
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
        'meta_keywords',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable' => false,
        ),
        'Meta Keywords'
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
            'qm_metatager/category_metatag',
            array('category_id')
        ),
        array('category_id')
    )
    ->addIndex(
        $this->getIdxName(
            'qm_metatager/category_metatag',
            array('store_id')
        ),
        array('store_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'qm_metatager/category_metatag',
            'category_id',
            'catalog/category',
            'entity_id'
        ),
        'category_id',
        $this->getTable('catalog/category'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'qm_metatager/category_metatag',
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
$metatagTableName = 'qm_category_metatags_entity';
if ($installer->getConnection()->isTableExists($metatagTableName)) {
    $installer->run("INSERT INTO $tableName (category_id, store_id, meta_title, meta_keywords, meta_description)
        SELECT category_id, store_id, meta_title, meta_keywords, meta_description FROM $metatagTableName");
}
//end migrate

$installer->endSetup();