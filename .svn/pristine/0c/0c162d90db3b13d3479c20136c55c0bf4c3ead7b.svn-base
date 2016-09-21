<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('es_custommenu')};
CREATE TABLE {$this->getTable('es_custommenu')} (
  `custommenu_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `label` varchar(255) NOT NULL default '',
  `status` int(2) NOT NULL default 1,
  PRIMARY KEY (`custommenu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('es_custommenu_item')};
CREATE TABLE {$this->getTable('es_custommenu_item')} (
  `custommenu_item_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `menu_id` int(11) NOT NULL default 0,
  `parent_id` int(11) NOT NULL default 0,
  `url` varchar(255) default '',
  `cms_id` int(11) default 0,
  `target` varchar(255) default '',
  `order` varchar(255) NOT NULL default '',
  `status` int(2) NOT NULL  default 1,
  PRIMARY KEY (`custommenu_item_id`)
  
  #INDEX par_ind (menu_id),
  #FOREIGN KEY (menu_id) REFERENCES {$this->getTable('custommenu_item')}(custommenu_id)
  #  ON DELETE CASCADE
  #  ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup();
