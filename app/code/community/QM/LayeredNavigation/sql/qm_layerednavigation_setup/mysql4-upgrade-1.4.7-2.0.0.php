<?php
$this->startSetup();

$this->run("
    ALTER TABLE `{$this->getTable('qm_layerednavigation/filter')}` ADD `comment` TEXT NOT NULL;
    ALTER TABLE `{$this->getTable('qm_layerednavigation/filter')}` ADD `block_pos` VARCHAR(255) NOT NULL;
"); 

$this->endSetup();