<?php

$installer = $this;
$tableName = $installer->getTable('qm_metatager/product_image_alt');

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
        'value_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
        ),
        'Value Id'
    )
    ->addColumn(
        'label',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable' => true,
        ),
        'Image Alt'
    )
    ->addIndex(
        $this->getIdxName(
            'qm_metatager/product_image_alt',
            array('product_id')
        ),
        array('product_id')
    )
    ->addIndex(
        $this->getIdxName(
            'qm_metatager/product_image_alt',
            array('store_id')
        ),
        array('store_id')
    )
    /*
    ->addIndex(
        $this->getIdxName(
            'qm_metatager/product_image_alt',
            array('value_id')
        ),
        array('value_id')
    )*/
    ->addForeignKey(
        $this->getFkName(
            'qm_metatager/product_image_alt',
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
            'qm_metatager/product_image_alt',
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
    //при обновлении данных продукта value_id у него удаляется что приведет к удаление запомненного значения чего быть не должно
    /*->addForeignKey(
        $this->getFkName(
            'qm_metatager/product_image_alt',
            'value_id',
            'catalog/product_attribute_media_gallery_value',
            'value_id'
        ),
        'value_id',
        $this->getTable('catalog/product_attribute_media_gallery_value'),
        'value_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )*/
    ->setComment('Product Image Alt Table');

$installer->getConnection()->createTable($table);

$installer->endSetup();