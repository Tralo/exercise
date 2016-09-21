<?php

class ES_Custommenu_Helper_Data extends Mage_Core_Helper_Abstract {

  /**
   * Remove all sub menu items for parent item
   * 
   * @param type $parentId - parent item ID
   */
  public function removeChilds($parentId) {
    $collection = Mage::getModel('custommenu/custommenu_items')->getCollection()
            ->addFieldToFilter('parent_id', $parentId);

    if (count($collection) > 0) {
      foreach ($collection as $val) {
        $this->removeChilds($val->getId());
        $val->delete();
      }
    }
  }

  /**
   * Get tier 1 items of menu
   * 
   * @param type $menuLabel - Menu Label
   * @return ES_Custommenu_Model_Mysql4_Custommenu_Items_Collection collection of menu items 
   * @throws Zend_Exception
   */
  public function getMenuItems($menuLabel) {
    $menu = Mage::getModel('custommenu/custommenu')->load($menuLabel, 'label');

    if ($menu->getId()) {
      $collection = Mage::getModel('custommenu/custommenu_items')->getCollection()
              ->addFieldToSelect('*')
              ->addFieldToFilter('menu_id', $menu->getId())
              ->addFieldToFilter('parent_id', 0)
              ->addFieldToFilter('status', ES_Custommenu_Model_Status::STATUS_ENABLED)
              ->setOrder("position", 'asc');
      return $collection;
    } else {
      throw new Zend_Exception('Menu not exist.');
    }
  }

  /**
   * Get menu items of menu by parent menu item
   * 
   * @param type $menuItem
   * @return ES_Custommenu_Model_Mysql4_Custommenu_Items_Collection collection of menu items 
   */
  public function getMenuSubItems($menuItem) {
    $collection = Mage::getModel('custommenu/custommenu_items')->getCollection()
            ->addFieldToSelect('*')
            ->addFieldToFilter('parent_id', $menuItem->getId())
            ->addFieldToFilter('status', ES_Custommenu_Model_Status::STATUS_ENABLED)
            ->setOrder("position", 'asc');
    return $collection;
  }

}