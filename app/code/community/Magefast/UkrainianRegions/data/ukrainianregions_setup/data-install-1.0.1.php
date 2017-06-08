<?php
/**
 * Ukrainian Regions - Ukrainian Regions Module
 * Region code based on ISO_3166-2:UA http://uk.wikipedia.org/wiki/ISO_3166-2:UA
 *
 *
 * @title   Magento -> Ukrainian Regions
 * @category   Languages / Locales
 * @package Magefast_UkrainianRegions
 * @author  Alex
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
$installer->startSetup();


$installer->run("

INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-05', 'Вінницька область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Vinnytsia Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Вінницька область'), ('ru_RU', LAST_INSERT_ID(), 'Винницкая область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-07', 'Волинська область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Volyn Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Волинська область'), ('ru_RU', LAST_INSERT_ID(), 'Волынская область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-09', 'Луганська область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Luhansk Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Луганська область'), ('ru_RU', LAST_INSERT_ID(), 'Луганская область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-12', 'Дніпропетровська область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Dnipropetrovsk Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Дніпропетровська область'), ('ru_RU', LAST_INSERT_ID(), 'Днепропетровская область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-14', 'Донецька область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Donetsk Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Донецька область'), ('ru_RU', LAST_INSERT_ID(), 'Донецкая область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-18', 'Житомирська область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Zhytomyr Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Житомирська область'), ('ru_RU', LAST_INSERT_ID(), 'Житомирская область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-21', 'Закарпатська область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Zakarpattia Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Закарпатська область'), ('ru_RU', LAST_INSERT_ID(), 'Закарпатская область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-23', 'Запорізька область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Zaporizhia Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Запорізька область'), ('ru_RU', LAST_INSERT_ID(), 'Запорожская область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-26', 'Івано-Франківська область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Ivano-Frankivsk Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Івано-Франківська область'), ('ru_RU', LAST_INSERT_ID(), 'Ивано-Франковская область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-30', 'Київ');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Kiev'), ('uk_UA', LAST_INSERT_ID(), 'Київ'), ('ru_RU', LAST_INSERT_ID(), 'Киев');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-32', 'Київська область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Kiev Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Київська область'), ('ru_RU', LAST_INSERT_ID(), 'Киевская область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-35', 'Кіровоградська область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Kirovohrad Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Кіровоградська область'), ('ru_RU', LAST_INSERT_ID(), 'Кировоградская область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-40', 'Севастополь');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Sevastopol'), ('uk_UA', LAST_INSERT_ID(), 'Севастополь'), ('ru_RU', LAST_INSERT_ID(), 'Севастополь');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-43', 'Автономна Республіка Крим');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Autonomous Republic of Crimea'), ('uk_UA', LAST_INSERT_ID(), 'Автономна Республіка Крим'), ('ru_RU', LAST_INSERT_ID(), 'Автономная Республика Крым');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-46', 'Львівська область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Lviv Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Львівська область'), ('ru_RU', LAST_INSERT_ID(), 'Львовская область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-48', 'Миколаївська область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Mykolaiv Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Миколаївська область'), ('ru_RU', LAST_INSERT_ID(), 'Николаевская область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-51', 'Одеська область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Odessa Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Одеська область'), ('ru_RU', LAST_INSERT_ID(), 'Одесская область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-53', 'Полтавська область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Poltava Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Полтавська область'), ('ru_RU', LAST_INSERT_ID(), 'Полтавская область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-56', 'Рівненська область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Rivne Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Рівненська область'), ('ru_RU', LAST_INSERT_ID(), 'Ровненская область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-59', 'Сумська область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Sumy Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Сумська область'), ('ru_RU', LAST_INSERT_ID(), 'Сумская область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-61', 'Тернопільська область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Ternopil Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Тернопільська область'), ('ru_RU', LAST_INSERT_ID(), 'Тернопольская область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-63', 'Харківська область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Kharkiv Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Харківська область'), ('ru_RU', LAST_INSERT_ID(), 'Харьковская область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-65', 'Херсонська область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Kherson Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Херсонська область'), ('ru_RU', LAST_INSERT_ID(), 'Херсонская область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-68', 'Хмельницька область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Khmelnytskyi Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Хмельницька область'), ('ru_RU', LAST_INSERT_ID(), 'Хмельницкая область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-71', 'Черкаська область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Cherkasy Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Черкаська область'), ('ru_RU', LAST_INSERT_ID(), 'Черкасская область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-74', 'Чернігівська область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Chernihiv Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Чернігівська область'), ('ru_RU', LAST_INSERT_ID(), 'Черниговская область');


INSERT INTO `directory_country_region` (`country_id`, `code`, `default_name`) VALUES
      ('UA', 'UA-77', 'Чернівецька область');
INSERT INTO `directory_country_region_name` (`locale`, `region_id`, `name`) VALUES
      ('en_US', LAST_INSERT_ID(), 'Chernivtsi Oblast'), ('uk_UA', LAST_INSERT_ID(), 'Чернівецька область'), ('ru_RU', LAST_INSERT_ID(), 'Черновицкая область');

");


$installer->endSetup();



