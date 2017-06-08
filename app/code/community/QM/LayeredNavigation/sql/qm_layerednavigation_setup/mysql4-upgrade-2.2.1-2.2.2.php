<?php
$this->startSetup();

$this->run("
    ALTER TABLE `{$this->getTable('qm_layerednavigation/value')}`
    ADD COLUMN `img_small_hover` VARCHAR(255) NOT NULL
"); 

$this->endSetup();