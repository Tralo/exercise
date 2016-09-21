<?php

class ES_Custommenu_Model_Status extends Varien_Object {

  const STATUS_ENABLED = 1;  // Menu status enabled
  const STATUS_DISABLED = 2;  // Menu status disabled

  /**
   * Get status option arrays for status drop down in admin
   * 
   * @return option array
   */

  static public function getOptionArray() {
    return array(
        self::STATUS_ENABLED => Mage::helper('custommenu')->__('Enabled'),
        self::STATUS_DISABLED => Mage::helper('custommenu')->__('Disabled')
    );
  }

}