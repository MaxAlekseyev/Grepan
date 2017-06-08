<?php

$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('qmoneclickorder/order'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Order ID')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Name')
    ->addColumn('telephone', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Telephone')
    ->addColumn('comment', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        ), 'Comment')
    ->addColumn('product_name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Product Name')
    ->addColumn('product_sku', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Product Sku')
    ->addColumn('product_price', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        ), 'Product Price')
     ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        ), 'Order Status')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            ), 'Order Modification Time')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Order Creation Time') 
    ->setComment('Order Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('qmoneclickorder/order_store'))
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'primary'   => true,
        ), 'Order ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Store ID')
    ->addIndex($this->getIdxName('qmoneclickorder/order_store', array('store_id')), array('store_id'))
    ->addForeignKey($this->getFkName('qmoneclickorder/order_store', 'order_id', 'qmoneclickorder/order', 'entity_id'), 'order_id', $this->getTable('qmoneclickorder/order'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($this->getFkName('qmoneclickorder/order_store', 'store_id', 'core/store', 'store_id'), 'store_id', $this->getTable('core/store'), 'store_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Orders To Store Linkage Table');
$this->getConnection()->createTable($table);
$this->endSetup();
