<?php

$systemConfig = Mage::getModel('core/config');

$systemConfig->saveConfig('checkout/options/onepage_checkout_enabled', 0);