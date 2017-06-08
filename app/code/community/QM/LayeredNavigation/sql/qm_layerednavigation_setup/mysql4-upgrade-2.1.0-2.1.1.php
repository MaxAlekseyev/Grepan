<?php
$this->startSetup();

$this->run("
    ALTER TABLE `{$this->getTable('qm_layerednavigation/filter')}` ADD COLUMN `depend_on_attribute` VARCHAR(256) NOT NULL;
"); 

$this->endSetup();