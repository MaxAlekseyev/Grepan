<?php
$this->startSetup();

$this->run("

ALTER TABLE `{$this->getTable('qm_layerednavigation/filter')}` ADD `show_search` TINYINT( 1 ) NOT NULL ,
ADD `slider_decimal` TINYINT( 1 ) NOT NULL ;


");
 
$this->endSetup();