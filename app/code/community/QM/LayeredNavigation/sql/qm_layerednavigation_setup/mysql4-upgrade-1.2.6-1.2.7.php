<?php
$this->startSetup();

$this->run("
    ALTER TABLE `{$this->getTable('qm_layerednavigation/filter')}` ADD `exclude_from` VARCHAR(255) NOT NULL;
"); 

$this->endSetup();