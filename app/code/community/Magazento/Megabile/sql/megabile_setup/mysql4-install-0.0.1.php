<?php

$installer = $this;

$installer->startSetup();
$installer->run("


CREATE TABLE IF NOT EXISTS `{$this->getTable('magazento_megabile_item')}` (
  `item_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT ' Id',
  `products` text NOT NULL,
  `description_field` varchar(250) NOT NULL,
  `use_attributes` tinyint(4) NOT NULL,
  `root_category` int(11) NOT NULL,
  `products_for_export` smallint(6) NOT NULL,
  `attributes` text NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `store_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Store id',
  `from_time` datetime NOT NULL,
  PRIMARY KEY (`item_id`),
  KEY `IDX_SITEMAP_STORE_ID` (`store_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;


");



$installer->endSetup();
