<?php

class CJM_ColorSelectorPlus_Block_Catalog_Product_View_Type_Configurable extends Mage_Catalog_Block_Product_View_Type_Configurable
{
	public function getJsonConfig()
    {
        $config = parent::getJsonConfig();
        $config = Mage::helper('core')->jsonDecode($config);
        $attributes = $config['attributes'];
        //DH_MOD 设置默认值
        $_product = $this->getProduct();
        $default_lentgh = $_product->getData("default_length");
        $default_color = $_product->getData("default_color");
        
        //DH_END
        foreach ($this->getAllowAttributes() as $attribute):
            $productAttribute = $attribute->getProductAttribute();
            $attributeId = $productAttribute->getId();
            $theCount = count($attributes[$attributeId]['options']);
            //DH_MOD
            if($productAttribute->getAttributeCode() == 'xlength'){
                $default_label = $default_lentgh;
            }
            if($productAttribute->getAttributeCode() == 'color'){
                $default_label = $default_color;
            }
            //如果有选项， 有默认值
            if( $theCount > 0 && !is_null($default_label)){
                $attributes[$attributeId]['preselect'] = $this->getOptionIdByLabel($attributes[$attributeId]['options'], $default_label);
            }
//            if (isset($attributes[$attributeId])){ $attributes[$attributeId]['preselect'] = $theCount > 1 ? $attribute->getPreselect() : 'one'; }
            //DH_END
        endforeach;
        $config['attributes'] = $attributes;
        return Mage::helper('core')->jsonEncode($config);
    }
    //DH_MOD
    public function getOptionIdByLabel($options, $default_label)
    {
        $default_id = null;
        foreach($options as $optoin){
            if($optoin['label'] == trim($default_label)){
                $default_id = $optoin['id'];
            }
        }
          //uk和品牌站下拉默认选项修改,如果default_length或者default_color没值或对不上,就取价格+0的
        if(!$default_id){
            foreach($options as $optoin2){
                if($optoin2['price'] ==0){
                    $default_id = $optoin2['id'];
                }
            }
       }   
        
        return $default_id;
    }
    //DH_END
    
}