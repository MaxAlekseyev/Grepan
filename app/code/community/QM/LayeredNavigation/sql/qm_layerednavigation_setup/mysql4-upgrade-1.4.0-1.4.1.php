<?php
$this->startSetup();

$this->run("
    ALTER TABLE `{$this->getTable('qm_layerednavigation/value')}` ADD `cms_block` VARCHAR(255);
"); 

$this->endSetup();