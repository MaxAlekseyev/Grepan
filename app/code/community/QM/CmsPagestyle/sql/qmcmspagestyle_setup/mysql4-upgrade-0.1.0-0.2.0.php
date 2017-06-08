<?php

$installer = $this;
$installer->startSetup();

$installer->run("
    ALTER TABLE `{$this->getTable('cms/page')}` ADD `page_js` TEXT NOT NULL;
");

$installer->endSetup();