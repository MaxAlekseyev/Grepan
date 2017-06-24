#!/bin/bash

sharedDir=/home/magento/www/grepan.ua/shared
workingDir=/home/magento/www/grepan.ua/releases/maxa

ln -s ${workingDir}/apc.php ${sharedDir}/apc.php
ln -s ${workingDir}/app/etc/local.xml ${sharedDir}/app/etc/local.xml


ln -s ${workingDir}/media ${sharedDir}/media
ln -s ${workingDir}/sitemaps ${sharedDir}/sitemaps
ln -s ${workingDir}/staging ${sharedDir}/staging
ln -s ${workingDir}/var ${sharedDir}/var
