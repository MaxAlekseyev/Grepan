<?php

/**
 * Banners module install script
 *
 * @category    QM
 * @package     QM_Banners
 *
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('qm_banners/banner'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity' => true,
            'nullable' => false,
            'primary' => true,
        ),
        'Banner ID'
    )
    ->addColumn(
        'name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable' => false,
        ),
        'Name'
    )
    ->addColumn(
        'width',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable' => false,
        ),
        'Width'
    )
    ->addColumn(
        'height',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable' => false,
        ),
        'Height'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Banner Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Banner Creation Time'
    )
    ->setComment('Banner Table');
$this->getConnection()->createTable($table);


$table = $this->getConnection()
    ->newTable($this->getTable('qm_banners/render'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity' => true,
            'nullable' => false,
            'primary' => true,
        ),
        'Render ID'
    )
    ->addColumn(
        'banner_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'nullable' => false,
        ),
        'Banner ID'
    )
    ->addColumn(
        'store_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'nullable' => false,
        ),
        'Banner ID'
    )
    ->addColumn(
        'images',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(
            'nullable' => false,
        ),
        'Images'
    )
    ->addColumn(
        'html_render',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(
            'nullable' => false,
        ),
        'Hmtl Render'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Render Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Render Creation Time'
    )
    ->setComment('Render Table');
$this->getConnection()->createTable($table);

$table = $this->getConnection()
    ->newTable($this->getTable('qm_banners/banner_store'))
    ->addColumn(
        'banner_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'nullable' => false,
            'primary' => true,
        ),
        'Banner ID'
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
    ->addIndex(
        $this->getIdxName(
            'qm_banners/banner_store',
            array('store_id')
        ),
        array('store_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'qm_banners/banner_store',
            'banner_id',
            'qm_banners/banner',
            'entity_id'
        ),
        'banner_id',
        $this->getTable('qm_banners/banner'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'qm_banners/banner_store',
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
    ->setComment('Banner To Store Linkage Table');
$this->getConnection()->createTable($table);

$table = $this->getConnection()
    ->newTable($this->getTable('qm_banners/image'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity' => true,
            'nullable' => false,
            'primary' => true,
        ),
        'Image ID'
    )
    ->addColumn(
        'image_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'nullable' => false
        ),
        'Image ID inside banner'
    )
    ->addColumn(
        'banner_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'nullable' => false,
        ),
        'Banner ID'
    )
    ->addColumn(
        'origin_path',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable' => false,
        ),
        'Origin path'
    )
    ->addColumn(
        'url',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable' => false,
        ),
        'Origin path'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Image Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Image Creation Time'
    )
    ->setComment('Image Table');
$this->getConnection()->createTable($table);

$this->endSetup();
