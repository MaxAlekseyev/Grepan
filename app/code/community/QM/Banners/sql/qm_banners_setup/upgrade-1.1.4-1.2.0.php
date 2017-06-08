<?php

$this->startSetup();
$tableName = $this->getTable('qm_banners/render');

$this->getConnection()
    ->addColumn($tableName,
                'css_style',
                array(
                    'comment'  => 'Css style',
                    'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
                    'nullable' => false,
                    'length'   => '64k',
                )
    );
$this->endSetup();
