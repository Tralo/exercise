<?php

class CJM_ColorSelectorPlus_Model_Observer
{	
	public function core_collection_abstract_load_after($observer)
	{
		$collection = $observer->getCollection();
		if (get_class($collection) == 'Mage_Catalog_Model_Resource_Product_Type_Configurable_Attribute_Collection') {
        	if ($collection->count()) {
        		$labelTable = $collection->getTable('catalog/product_super_attribute_label');
	            $select = $collection->getConnection()->select()
	                ->from(array('default'=>$labelTable))
	                ->joinLeft(array('store' => $labelTable), 'store.product_super_attribute_id=default.product_super_attribute_id AND store.store_id='.$collection->getStoreId(), array('use_default' => new Zend_Db_Expr('IFNULL(store.use_default, default.use_default)'), 'label' => new Zend_Db_Expr('IFNULL(store.value, default.value)')))
	                ->where('default.product_super_attribute_id IN (?)', array_keys($collection->getItems()))
	                ->where('default.store_id=0');
	                foreach ($collection->getConnection()->fetchAll($select) as $data) {
	                    $collection->getItemById($data['product_super_attribute_id'])->setPreselect($data['preselect']);
	                }
	        }
        }
	}
	
	public function model_save_after($observer)
	{
		$attribute = $observer->getObject();
		if (get_class($attribute) == 'Mage_Catalog_Model_Product_Type_Configurable_Attribute')
        {
        	$write = Mage::getSingleton('core/resource')->getConnection('core_write');
        	$labelTable = $attribute->getResource()->getTable('catalog/product_super_attribute_label');
        	$select = $write->select()
	            ->from($labelTable, 'value_id')
	            ->where('product_super_attribute_id=?', $attribute->getId())
	            ->where('store_id=?', (int)$attribute->getStoreId());
	        if ($valueId = $write->fetchOne($select)) {
	        	$write->update($labelTable,array('preselect' => (int) $attribute->getPreselect()), $write->quoteInto('value_id=?', $valueId)); }
	        else {
	            $write->insert($labelTable, array(
					'product_super_attribute_id' => $attribute->getId(),
	                'store_id' => (int) $attribute->getStoreId(),
	                'use_default' => (int) $attribute->getUseDefault(),
	                'value' => $attribute->getLabel(),
	                'preselect' => (int) $attribute->getPreselect()
	            ));
	        }
        }
	}
        
        //旧的ID换成新的ID
        public function attribute_map($observer)
        {
            $controller = $observer->getEvent()->getFront();
            $request = Mage::app()->getRequest();
            foreach($this->getMaps() as $attribute_code=>$map)
            {
                $var = $request->getParam($attribute_code);
                if(!$var){
                    continue;
                }
                $request->setParam($attribute_code, array_key_exists($var, $map) ? $map[$var] : $var);
            }
        }
                
                
                
    //产品属性值硬编码 oldid=>newid
    public function getMaps(){
        
        return array(
            'length'=>array(
                199=>327,
                198=>328,
                256=>332,
                197=>329,
                257=>333,
                267=>336,
                195=>320,
                258=>334,
                194=>321,
                259=>335,
                193=>322,
                192=>323,
                191=>324,
                190=>325,
                202=>330,
                201=>331,
                200=>326
            ),
            
            'color_two'=>array(
                142=>384,
                260=>392,
                152=>395,
                154=>385,
                155=>396,
                157=>386,
                161=>376,
                163=>377,
                165=>383,
                166=>393,
                167=>378,
                168=>387,
                171=>381,
                172=>397,
                365=>379,
                177=>394,
                178=>380,
                179=>388,
                180=>382,
                181=>398,
                264=>400,
                183=>389,
                266=>403,
                265=>402,
                187=>401,
                186=>390,
                189=>391,
                263=>399
            ),
            'hair_type'=>array(
                311=>288,
                315=>264,
                291=>274,
                294=>231,
                325=>191,
                295=>237,
                293=>230,
                296=>275,
                326=>249,
                327=>277,
                312=>281,
//                288=>290,
                287=>289
            ),
            'quantity'=>array(
                340=>319,
                374=>293,
                372=>206,
                277=>250,
                362=>220,
                332=>282,
                358=>238,
                335=>285,
                334=>286,
                376=>308,
                333=>287,
                375=>317,
                301=>193,
                373=>213                
            ),
            'texture'=>array(
                206=>198,
                207=>244,
                330=>280,
                378=>292,
                329=>283,
                331=>284,
                208=>192                
            )
        );
    }                
}