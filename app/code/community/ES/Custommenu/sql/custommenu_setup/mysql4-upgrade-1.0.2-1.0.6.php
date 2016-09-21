<?php

$installer = $this;

$installer->startSetup();

$installer->run("
  ALTER TABLE {$this->getTable('es_custommenu_item')} CHANGE COLUMN {$this->getTable('es_custommenu_item')}.order {$this->getTable('es_custommenu_item')}.position INT(4);
");

$installer->endSetup();
