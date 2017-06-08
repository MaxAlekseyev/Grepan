<?php

$this->startSetup();
$this->getConnection()
    ->addColumn($this->getTable('qmadvfeedback/callback'),
                'spam',
                array(
                    'comment'  => 'Is Spam',
                    'type'     => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                    'nullable' => false
                )
    );

$this->getConnection()
    ->addColumn($this->getTable('qmadvfeedback/consultation'),
                'spam',
                array(
                    'comment'  => 'Is Spam',
                    'type'     => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                    'nullable' => false
                )
    );

$this->endSetup();
