#!/bin/bash

sharedDir=/home/magento/www/grepan.ua/shared
workingDir=/home/magento/www/grepan.ua/releases/max.a

ln -sf ${sharedDir}/apc.php ${workingDir}/apc.php
ln -sf ${sharedDir}/app/etc/local.xml ${workingDir}/app/etc/local.xml


ln -sf ${sharedDir}/media ${workingDir}/media
ln -sf ${sharedDir}/sitemaps ${workingDir}/sitemaps
ln -sf ${sharedDir}/staging ${workingDir}/staging
ln -sf ${sharedDir}/var ${workingDir}/var
