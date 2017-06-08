<?php

$this->startSetup();
$tableName = $this->getTable('qm_banners/banner');

$this->getConnection()
    ->addColumn($tableName,
                'css_class',
                array(
                    'comment'  => 'Css class',
                    'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
                    'nullable' => false,
                    'length'   => 255,
                )
    );
$this->endSetup();
