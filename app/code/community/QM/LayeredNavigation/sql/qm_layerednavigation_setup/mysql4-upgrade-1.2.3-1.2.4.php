<?php
$this->startSetup();

$this->run("
    ALTER TABLE `{$this->getTable('qm_layerednavigation/filter')}` ADD `max_options` SMALLINT NOT NULL AFTER `attribute_id`
");

$this->endSetup();