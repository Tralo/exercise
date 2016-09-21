<?php

class ES_Custommenu_Model_Custommenu_Items extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('custommenu/custommenu_items');
    }
    
    public function getInputSelectParentItem()
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
    
    public function getInputSelectPages()
    {
      $output = array();
      $output[] = array('value' => '', 'label' => 'Select');
      
      $collection = Mage::getModel('cms/page')->getCollection()
              ->addFieldToSelect('*')
              ->setOrder('title', 'asc');
      
      if(count($collection) > 0)
      {
        foreach($collection as $page)
        {
          $output[] = array('value' => $page->getId(), 'label' => $page->getTitle());
        }
      }
      
      return $output;
    }
    
    public function getGridInputSelectPages()
    {
      $output = array();
      $output[''] = 'Select';
      
      $collection = Mage::getModel('cms/page')->getCollection()
              ->addFieldToSelect('*')
              ->setOrder('title', 'asc');
      
      if(count($collection) > 0)
      {
        foreach($collection as $page)
        {
          $output[$page->getId()] = $page->getTitle();
        }
      }
      
      return $output;
    }
    
    public function getGridInputSelectParentItem()
    {
      $output = array();
      $output[0] = 'None';
      
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
    
    public function getRealUrl()
    {
      if($this->getCmsId())
      {
        $pageUrl = Mage::getModel('cms/page')->load($this->getCmsId())->getIdentifier();
        return Mage::getUrl() . (($pageUrl == 'home')?'':$pageUrl);
      }
      return $this->getUrl();
    }
    
    public function getTargetHtml()
    {
      if($this->getTarget() != '')
      {
        return ' target="' . $this->getTarget() . '"';
      }
    }
    
    public function getLevel($level = 0)
    {
      if($this->getParentId() == 0){
        return $level;
      }
      $level++;
      $menu = Mage::getModel('custommenu/custommenu_items')->load($this->getParentId());
      return $menu->getLevel($level);
    }

}
?>