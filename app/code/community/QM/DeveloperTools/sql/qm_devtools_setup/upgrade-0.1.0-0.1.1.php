<?php
 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
	CREATE TABLE IF NOT EXISTS {$this->getTable('extension_conflict')} (
	`ec_id` INT NOT NULL AUTO_INCREMENT ,
	`ec_core_module` VARCHAR( 255 ) NOT NULL ,
	`ec_core_class` VARCHAR( 255 ) NOT NULL ,
	`ec_rewrite_classes` VARCHAR( 255 ) NOT NULL ,
	ec_is_conflict tinyint NOT NULL default 0,
	PRIMARY KEY ( `ec_id` )
	) ENGINE = MYISAM;

");

$installer->endSetup();