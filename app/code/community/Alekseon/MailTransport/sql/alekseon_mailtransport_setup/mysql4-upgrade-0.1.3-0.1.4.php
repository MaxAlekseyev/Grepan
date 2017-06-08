<?php
/**
 * @author    Marcin Frymark
 * @email     contact@alekseon.com
 * @company   Alekseon
 * @website   www.alekseon.com
 */
$installer = $this;
$installer->startSetup();
$installer->run("
    ALTER TABLE `{$this->getTable('alekseon_mailTransport/sentemail')}` ADD `controller` varchar(255) NOT NULL default '';
");
$installer->endSetup();