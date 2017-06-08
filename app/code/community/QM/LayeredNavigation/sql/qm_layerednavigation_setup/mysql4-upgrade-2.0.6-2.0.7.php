<?php
$this->startSetup();

$this->run("
    ALTER TABLE `{$this->getTable('qm_layerednavigation/filter')}` ADD COLUMN `range` int NOT NULL;
"); 

$this->endSetup();