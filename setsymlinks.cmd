#!/bin/bash

sharedDir=/home/magento/www/grepan.ua/shared
workingDir=/home/magento/www/grepan.ua/releases/maxa

ln -s ${sharedDir}/apc.php ${workingDir}/apc.php
ln -s ${sharedDir}/app/etc/local.xml ${workingDir}/app/etc/local.xml


ln -s ${sharedDir}/media ${workingDir}/media
ln -s ${sharedDir}/sitemaps ${workingDir}/sitemaps
ln -s ${sharedDir}/staging ${workingDir}/staging
ln -s ${sharedDir}/var ${workingDir}/var
