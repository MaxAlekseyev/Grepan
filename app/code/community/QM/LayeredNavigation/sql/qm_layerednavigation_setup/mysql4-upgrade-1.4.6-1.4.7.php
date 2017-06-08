<?php
$this->startSetup();

$this->run("
    ALTER TABLE `{$this->getTable('qm_layerednavigation/filter')}` ADD `depend_on`  VARCHAR(255) NOT NULL;
"); 

$this->endSetup();