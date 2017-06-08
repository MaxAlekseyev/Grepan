<?php
$this->startSetup();

$this->run("
    ALTER TABLE `{$this->getTable('qm_layerednavigation/filter')}` ADD COLUMN `backend_type` VARCHAR(45) NOT NULL DEFAULT '';
    ALTER TABLE `{$this->getTable('qm_layerednavigation/filter')}` ADD COLUMN `slider_type` TINYINT(1) NOT NULL;
    ALTER TABLE `{$this->getTable('qm_layerednavigation/filter')}` ADD COLUMN `from_to_widget` TINYINT(1) NOT NULL;
    ALTER TABLE `{$this->getTable('qm_layerednavigation/filter')}` ADD COLUMN `value_label` VARCHAR(16) NOT NULL;
"); 

$this->endSetup();