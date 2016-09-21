<?php

class CJM_ColorSelectorPlus_Block_Catalog_Layer_Filter_Attribute extends Mage_Catalog_Block_Layer_Filter_Attribute
{
    public function __construct()
    {
       
       parent::__construct();
        $this->setTemplate('colorselectorplus/filter.phtml'); 
    }
    
    public function getTheItems()
    {
        $items = array(); 
         $_filters = Mage::getBlockSingleton('catalog/layer_state')->getActiveFilters();
         
        foreach (parent::getItems() as $_item){
            if($_item->getCount() == 0){
                continue;
            }//如果没有产品则不显示
            $attributeCode = $_item->getFilter()->getAttributeModel()->getAttributeCode();
            $current_optionId = $_item->getValueString();
            $optionIds = explode(',', $current_optionId);
            $optionId = $optionIds[count($optionIds)-1];
            $replace_Id = str_replace(','.$optionId,'',$current_optionId);
            //选中的属性不存在ID，这里加上
            if($optionId==''){
                foreach ($_filters as $_filter){
                    if($attributeCode == $_filter->getAttributeCode() || $attributeCode == 'color_two'){
                       $optionId =  $_filter->getValueString();
                    }
                }
                
            }
            
            $attributeInfo = Mage::getResourceModel('eav/entity_attribute_collection')->setCodeFilter($attributeCode)->getFirstItem();
       		
       		$attributeId = $attributeInfo->getAttributeId();
     		$_collection = Mage::getResourceModel('eav/entity_attribute_option_collection')->setPositionOrder('asc')->setAttributeFilter($attributeId)->setStoreFilter(0)->load();
     		foreach( $_collection->toOptionArray() as $_cur_option ) {
				if($_cur_option['value']==$optionId){ $theLabel = $_cur_option['label']; }
			}
			
        	preg_match_all('/((#?[A-Za-z0-9]+))/', $theLabel, $matches);
        	
        	if ( count($matches[0]) > 0 ) {
        		$color_value = $matches[1][count($matches[0])-1];
				$findme = '#';
				$pos = strpos($color_value, $findme);
			} else {
				$pos = false; }

            $item = array();
            //多选 改成单选
            $item['url']   = $this->htmlEscape( count($optionIds)>1 ? str_replace(array($replace_Id,'%2C'), array('',''), $_item->getUrl()) :$_item->getUrl());
            $item['label'] = $_item->getLabel();
            $item['code'] = $attributeCode;
            $item['value'] = $_item->getValue();

            $item['count'] = '';
//            if (!$this->getHideCounts())
//                $item['count']  = ' (' . $_item->getCount() . ')';
            
            $item['image'] = '';
            $item['bgcolor'] = '';
           
            if(Mage::helper('colorselectorplus')->getSwatchUrl($optionId)):
                $item['image'] = Mage::helper('colorselectorplus')->getSwatchUrl($optionId);
            elseif($pos !== false):
                $item['bgcolor'] = $color_value;
           	else:
           		$item['image'] = Mage::helper('colorselectorplus')->getSwatchUrl('empty');
           	endif;
                
              //DH add for selected value
           if ($_item->getIsSelected() || $optionIds[0] == $optionId){
                $item['css'] .= '-selected';
                if (3 == $this->getDisplayType()) //dropdown
                    $item['css'] = 'selected';
            }
            //DH end
                
           
            $items[] = $item;
        }
        
        return $items;
    }
        
    public function getRequestValue()
    {
        return $this->_filter->getAttributeModel()->getAttributeCode();
    }
}