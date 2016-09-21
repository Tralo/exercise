<?php

class ES_Custommenu_Model_Mysql4_Custommenu_Items extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the bannerslider_id refers to the key field in your database table.
        $this->_init('custommenu/custommenu_items', 'custommenu_item_id');
    }
}