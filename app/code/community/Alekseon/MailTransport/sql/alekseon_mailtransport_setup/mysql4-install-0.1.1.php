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

CREATE TABLE {$this->getTable('alekseon_mailTransport/sentemail')} (
  `id` int(10) unsigned NOT NULL auto_increment,
  `store_id` smallint(5) unsigned NOT NULL default '0',
  `sent_at` datetime NOT NULL default '0000-00-00 00:00:00',
  `template_id` varchar(255) NOT NULL default '',
  `subject` varchar(255) NOT NULL default '',
  `mail_body` text NOT NULL default '',
  `transport_type` smallint(5) unsigned,
  `status` smallint(5) unsigned,
  `comment` text NOT NULL default '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE {$this->getTable('alekseon_mailTransport/sentemail_correspondent')} (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sentemail_id` int(10) unsigned,
  `type` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`sentemail_id`) REFERENCES `{$installer->getTable('alekseon_mailTransport/sentemail')}`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");
$installer->endSetup();
