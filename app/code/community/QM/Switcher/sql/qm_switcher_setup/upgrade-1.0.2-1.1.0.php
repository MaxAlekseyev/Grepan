<?php

$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('qm_switcher/hashcode'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Hash ID'
    )
    ->addColumn(
        'option_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        11,
        array(
            'nullable'  => false,
        ),
        'Option id'
    )
    ->addColumn('hashcode', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'hashcode')
    ->addIndex(
        $this->getIdxName(
            'qm_switcher/hashcode',
            array('option_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('option_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->setComment('Hashcode table');
$this->getConnection()->createTable($table);
$this->endSetup();
