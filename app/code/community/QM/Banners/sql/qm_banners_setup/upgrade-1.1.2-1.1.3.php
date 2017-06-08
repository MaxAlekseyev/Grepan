<?php

$this->startSetup();

$this->getConnection()->dropColumn($this->getTable('qm_banners/image'), 'url');

$this->endSetup();
