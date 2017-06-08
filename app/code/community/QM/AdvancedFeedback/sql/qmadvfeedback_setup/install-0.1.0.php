<?php

$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('qmadvfeedback/callback'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Callback ID')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Name')

    ->addColumn('telephone', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Telephone')

    ->addColumn('preferred_time', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Preferred Time')

    ->addColumn('comment', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        ), 'Comment')

    ->addColumn('page_url', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Page Url')

     ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        ), 'Callback Status')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            ), 'Callback Modification Time')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Callback Creation Time') 
    ->setComment('Callback Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('qmadvfeedback/consultation'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Сonsultation ID')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Name')

    ->addColumn('email', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Email')

    ->addColumn('telephone', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Telephone')

    ->addColumn('preferred_time', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Preferred Time')

    ->addColumn('comment', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        ), 'Comment')

    ->addColumn('page_url', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Page Url')

     ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        ), 'Сonsultation Status')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            ), 'Сonsultation Modification Time')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Сonsultation Creation Time') 
    ->setComment('Сonsultation Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('qmadvfeedback/callback_store'))
    ->addColumn('callback_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'primary'   => true,
        ), 'Callback ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Store ID')
    ->addIndex($this->getIdxName('qmadvfeedback/callback_store', array('store_id')), array('store_id'))
    ->addForeignKey($this->getFkName('qmadvfeedback/callback_store', 'callback_id', 'qmadvfeedback/callback', 'entity_id'), 'callback_id', $this->getTable('qmadvfeedback/callback'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($this->getFkName('qmadvfeedback/callback_store', 'store_id', 'core/store', 'store_id'), 'store_id', $this->getTable('core/store'), 'store_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Callbacks To Store Linkage Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('qmadvfeedback/consultation_store'))
    ->addColumn('consultation_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'primary'   => true,
        ), 'Сonsultation ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Store ID')
    ->addIndex($this->getIdxName('qmadvfeedback/consultation_store', array('store_id')), array('store_id'))
    ->addForeignKey($this->getFkName('qmadvfeedback/consultation_store', 'consultation_id', 'qmadvfeedback/consultation', 'entity_id'), 'consultation_id', $this->getTable('qmadvfeedback/consultation'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($this->getFkName('qmadvfeedback/consultation_store', 'store_id', 'core/store', 'store_id'), 'store_id', $this->getTable('core/store'), 'store_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Сonsultations To Store Linkage Table');
$this->getConnection()->createTable($table);
$this->endSetup();
