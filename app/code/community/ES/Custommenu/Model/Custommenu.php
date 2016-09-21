<?php

class ES_Custommenu_Model_Custommenu extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('custommenu/custommenu');
    }
    
    public function getInputSelectMenu()
    {
      $output = array();
      $output[] = array('value' => '', 'label' => 'Select');
      
      $collection = $this->getCollection()
              ->addFieldToSelect('*')
              ->setOrder('name', 'asc');
      
      if(count($collection) > 0)
      {
        foreach($collection as $menu)
        {
          $output[] = array('value' => $menu->getId(), 'label' => $menu->getName());
        }
      }
      
      return $output;
    }
    
    public function getGridInputSelectMenu()
    {
      $output = array();
      $collection = $this->getCollection()
              ->addFieldToSelect('*')
              ->setOrder('name', 'asc');
      
      if(count($collection) > 0)
      {
        foreach($collection as $menu)
        {
          $output[$menu->getId()] = $menu->getName();
        }
      }
      
      return $output;
    }
}
?>