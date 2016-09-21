<?php

class ES_Custommenu_Block_Custommenu extends Mage_Core_Block_Template {

  /**
   * Prepare layout and set general template for menus
   * 
   * @return type
   */
  public function _prepareLayout() {
    $this->setTemplate('es_custommenu/menu.phtml');
    return parent::_prepareLayout();
  }

  /**
   * Get menu items of menu by menu label
   * 
   * @param type $menuLabel
   * @return ES_Custommenu_Model_Mysql4_Custommenu_Items_Collection collection of menu items 
   */
  public function getMenuItems($menuLabel) {
    return Mage::helper('custommenu')->getMenuItems($menuLabel);
  }

  /**
   * Get submenu item block and html
   * 
   * @param type ES_Custommenu_Block_Custommenu
   */
  public function getSubMenuBlock($menuItem) {
    $subMenuBlock = $this->getLayout()->createBlock('custommenu/custommenu')->setTemplate($this->_getSubmenuTemplate());
    $subMenuBlock->setParentMenu($menuItem);
    return $subMenuBlock;
  }

  /**
   * Get menu items of menu by parent menu $menuItem
   * 
   * @param type $menuLabel
   * @return ES_Custommenu_Model_Mysql4_Custommenu_Items_Collection collection of menu items 
   */
  public function getMenuSubItems($menuItem) {
    return Mage::helper('custommenu')->getMenuSubItems($menuItem);
  }

  /**
   * Get submenu template if is seted
   * 
   * @return String Submenu Template
   */
  protected function _getSubmenuTemplate(){
    if($this->getSubmenuTemplate() == null){
      return 'es_custommenu/submenu.phtml';
    }
    return $this->getSubmenuTemplate();
  }

}