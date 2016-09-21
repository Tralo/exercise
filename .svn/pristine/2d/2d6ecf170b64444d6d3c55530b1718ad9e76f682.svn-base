<?php
/**
*@autho Gordon
* 
*/

$installer = $this;

$installer->startSetup();

Mage::helper( 'xpbase/mysql4_install' )->addColumns( $installer, $this->getTable( 'cms_page' ), 
    array(
        "`meta_title` TEXT",
    ) );


$installer->endSetup();

