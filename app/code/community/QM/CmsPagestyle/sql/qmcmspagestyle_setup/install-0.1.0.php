<?php

$installer = $this;
$installer->startSetup();

$installer->run("
    ALTER TABLE `{$this->getTable('cms/page')}` ADD `page_css` TEXT NOT NULL;
");

$installer->endSetup();