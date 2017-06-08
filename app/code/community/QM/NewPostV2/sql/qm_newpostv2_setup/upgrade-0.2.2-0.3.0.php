<?php

$installer = $this;

$installer->startSetup();
$tableCityName = $installer->getTable('qm_newpostv2/table_city');
$tableCityRefIndex = $installer->getIdxName(
    'qm_newpostv2/table_city',
    array('ref'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$sql=<<<SQLTEXT
CREATE INDEX $tableCityRefIndex ON $tableCityName (ref(255));
SQLTEXT;

$installer->run($sql);

$tableWaybillName = $installer->getTable('qm_newpostv2/table_waybill');

$installer->getConnection()->dropTable($tableWaybillName);

$tableWaybill = $installer->getConnection()
    ->newTable($tableWaybillName)
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
    ))
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ))
    ->addColumn('volume_general', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable' => false,
    ))
    ->addColumn('weight', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable' => false,
    ))
    ->addColumn('seats_amount', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
    ))
    ->addColumn('cost', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable' => false,
    ))
    ->addColumn('city_sender', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
    ))
    ->addColumn('city_recipient', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
    ));

foreach (['payer_type', 'payment_method', 'date_time', 'cargo_type',
             'service_type', 'description', 'sender', 'sender_address',
             'contact_sender', 'senders_phone', 'recipient', 'recipient_address',
             'contact_recipient', 'recipients_phone', 'waybill_ref'] as $field) {
    $tableWaybill = $tableWaybill->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
    ));
}

$installer->getConnection()->createTable($tableWaybill);

$tableWaybillIndexSender = $this->getIdxName(
    'qm_newpostv2/table_waybill',
    array('city_sender')
);
$tableWaybillIndexRecipient = $this->getIdxName(
    'qm_newpostv2/table_waybill',
    array('city_recipient')
);

$tableWaybillConstrainSender = $this->getFkName(
    'qm_newpostv2/table_waybill',
    'city_sender',
    'qm_newpostv2/table_city',
    'ref'
);

$tableWaybillConstrainRecipient = $this->getFkName(
    'qm_newpostv2/table_waybill',
    'city_recipient',
    'qm_newpostv2/table_city',
    'ref'
);

$sql=<<<SQLTEXT
CREATE INDEX $tableWaybillIndexSender ON $tableWaybillName (city_sender(255));
ALTER TABLE $tableWaybillName ADD CONSTRAINT $tableWaybillConstrainSender FOREIGN KEY (city_sender) REFERENCES $tableCityName (ref) ON DELETE CASCADE;

CREATE INDEX $tableWaybillIndexRecipient ON $tableWaybillName (city_recipient(255));
ALTER TABLE $tableWaybillName ADD CONSTRAINT $tableWaybillConstrainRecipient FOREIGN KEY (city_recipient) REFERENCES $tableCityName (ref) ON DELETE CASCADE;

SQLTEXT;

$installer->run($sql);

$installer->endSetup();