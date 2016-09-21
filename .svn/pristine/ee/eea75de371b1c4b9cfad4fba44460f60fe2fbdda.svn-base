<?php

$installer = $this;

$installer->startSetup();

$installer->run("
  ALTER TABLE {$this->getTable('es_custommenu_item')} ADD COLUMN category_id int(11);
");

$installer->endSetup();
