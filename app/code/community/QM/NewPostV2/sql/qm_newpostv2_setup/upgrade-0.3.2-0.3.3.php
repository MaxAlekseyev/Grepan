<?php

$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('qm_newpostv2/table_waybill'),
    'waybill_ref',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => false,
        'comment' => 'Waybill ref'
    )
);
$installer->getConnection()->addColumn($installer->getTable('qm_newpostv2/table_waybill'),
    'waybill_number',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => false,
        'comment' => 'Waybill ref'
    )
);

$installer->endSetup();