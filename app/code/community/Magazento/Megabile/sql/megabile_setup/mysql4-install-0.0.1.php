<?php

$installer = $this;

$installer->startSetup();
$installer->installEntities();



$installer->run("



CREATE TABLE IF NOT EXISTS `{$this->getTable('magazento_megabile_item')}` (
  `item_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT ' Id',
  `products` text NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `rule_title` varchar(255) DEFAULT NULL COMMENT 'Path',
  `rule_url` varchar(255) DEFAULT NULL,
  `rule_description` varchar(255) DEFAULT NULL,
  `rule_keywords` varchar(255) DEFAULT NULL,
  `store_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Store id',
  PRIMARY KEY (`item_id`),
  KEY `IDX_SITEMAP_STORE_ID` (`store_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `{$this->getTable('magazento_megabile_item')}`
--





");



$installer->endSetup();
