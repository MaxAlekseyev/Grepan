<?php

$this->startSetup();

$table = $this->getConnection()
    ->newTable($this->getTable('qm_ordertracker/tracking'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Entity ID')
    ->addColumn('is_supported', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
        'nullable' => false,
    ), 'Status')
    ->addColumn('is_available', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
        'nullable' => false,
    ), 'Status')
    ->addColumn('not_available_message', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
    ), 'Status')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
    ), 'Status')
    ->addColumn('address', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
    ), 'Address')
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ), 'Entity ID')
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Modification Time'
    )
    ->addIndex($this->getIdxName('qm_ordertracker/tracking', array('order_id')), array('order_id'))
    ->addForeignKey($this->getFkName('qm_ordertracker/tracking', 'order_id', 'sales/quote', 'entity_id'), 'order_id', $this->getTable('sales/quote'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Order Relations');
$this->getConnection()->createTable($table);

$this->endSetup();