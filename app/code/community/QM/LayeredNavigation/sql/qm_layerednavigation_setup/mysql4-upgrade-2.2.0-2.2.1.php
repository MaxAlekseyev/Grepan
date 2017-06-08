<?php
$this->startSetup();

$this->run("
    ALTER TABLE `{$this->getTable('qm_layerednavigation/page')}`
    ADD COLUMN `store_id` SMALLINT(5) UNSIGNED DEFAULT 0 AFTER `page_id`,
    ADD KEY `IDX_LAYEREDNAVIGATION_PAGE_STORE_VIEW_ID` (`store_id`),
    ADD CONSTRAINT `FK_LAYEREDNAVIGATION_PAGE_CORE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON DELETE SET NULL;
"); 

$this->endSetup();