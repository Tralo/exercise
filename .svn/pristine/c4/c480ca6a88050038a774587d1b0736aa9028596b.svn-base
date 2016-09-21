<?php
class Magmi_ConfigurableItemProcessor extends Magmi_ItemProcessor
{

    
    private $_configurable_attrs=array();
    private $_use_defaultopc=false;
    private $_optpriceinfo=array();
    private $_currentsimples=array();
    
    private $simple_data_temp =array();
    public function initialize($params)
    {
            
    }
    
    public function getPluginInfo()
    {
        return array(
            "name" => "Configurable Item processor",
            "author" => "Dweeves",
            "version" => "1.3.7",
            "url"=> $this->pluginDocUrl("Configurable_Item_processor")
            );
    }
    
public function getConfigurableOptsFromAsId($asid)
{
    if(!isset($this->_configurable_attrs[$asid]))
    {
        $ea=$this->tablename("eav_attribute");
        $eea=$this->tablename("eav_entity_attribute");
        $eas=$this->tablename("eav_attribute_set");
        $eet=$this->tablename("eav_entity_type");
    
        $sql="SELECT ea.attribute_code FROM `$ea` as ea
        JOIN $eet as eet ON eet.entity_type_id=ea.entity_type_id AND eet.entity_type_id=?
        JOIN $eas as eas ON eas.entity_type_id=eet.entity_type_id AND eas.attribute_set_id=?
        JOIN $eea as eea ON eea.attribute_id=ea.attribute_id";
        $cond="ea.is_user_defined=1";
        if($this->getMagentoVersion()!="1.3.x")
        {
            $cea=$this->tablename("catalog_eav_attribute");
            $sql.=" JOIN $cea as cea ON cea.attribute_id=ea.attribute_id AND cea.is_global=1 AND cea.is_configurable=1";
        }
        else
        {
            $cond.=" AND ea.is_global=1 AND ea.is_configurable=1";
        }
        $sql.=" WHERE $cond
            GROUP by ea.attribute_id";

        $result=$this->selectAll($sql,array($this->getProductEntityType(),$asid));
        foreach($result as $r)
        {
            $this->_configurable_attrs[$asid][]=$r["attribute_code"];
        }
    }   
    return $this->_configurable_attrs[$asid];
}

    
    public function dolink($pid,$cond,$conddata=array())
    {
            $cpsl=$this->tablename("catalog_product_super_link");
            $cpr=$this->tablename("catalog_product_relation");
            $cpe=$this->tablename("catalog_product_entity");
            $sql="DELETE cpsl.*,cpsr.* FROM $cpsl as cpsl
                JOIN $cpr as cpsr ON cpsr.parent_id=cpsl.parent_id
                WHERE cpsl.parent_id=?";
            $this->delete($sql,array($pid));
            //recreate associations
            $sql="INSERT INTO $cpsl (`parent_id`,`product_id`) SELECT cpec.entity_id as parent_id,cpes.entity_id  as product_id  
                  FROM $cpe as cpec 
                  JOIN $cpe as cpes ON cpes.type_id IN ('simple','virtual') AND cpes.sku $cond
                  WHERE cpec.entity_id=?";
            $this->insert($sql,array_merge($conddata,array($pid)));
            $sql="INSERT INTO $cpr (`parent_id`,`child_id`) SELECT cpec.entity_id as parent_id,cpes.entity_id  as child_id  
                  FROM $cpe as cpec 
                  JOIN $cpe as cpes ON cpes.type_id IN ('simple','virtual') AND cpes.sku $cond
                  WHERE cpec.entity_id=?";
            $this->insert($sql,array_merge($conddata,array($pid)));
            unset($conddata);
    }
    
    
    public function autoLink($pid)
    {
        $this->dolink($pid,"LIKE CONCAT(cpec.sku,'%')");
    }
    
    public function updSimpleVisibility($pid)
    {
        $vis=$this->getParam("CFGR:updsimplevis",0);
        if($vis!=0)
        {
            $attinfo=$this->getAttrInfo("visibility");
            $sql="UPDATE ".$this->tablename("catalog_product_entity_int")." as cpei
            JOIN ".$this->tablename("catalog_product_super_link"). " as cpsl ON cpsl.parent_id=?
            JOIN ".$this->tablename("catalog_product_entity")." as cpe ON cpe.entity_id=cpsl.product_id 
            SET cpei.value=?
            WHERE cpei.entity_id=cpe.entity_id AND attribute_id=?";
            $this->update($sql,array($pid,$vis,$attinfo["attribute_id"]));
        }
    }
    
    public function fixedLink($pid,$skulist)
    {
        $this->dolink($pid,"IN (".$this->arr2values($skulist).")",$skulist);        
    }
    
    public function buildSAPTable($sapdesc)
    {
        $saptable=array();
        $sapentries=explode(";;",$sapdesc); // replace , => ;;
        foreach($sapentries as $sapentry)
        {
            $sapinf=explode("::",$sapentry);
            $sapname=$sapinf[0];
            $sapdata=$sapinf[1];
            $sapdarr=explode(";",$sapdata);
            $saptable[$sapname]=$sapdarr;
            unset($sapdarr);
        }
        unset($sapentries);
        return $saptable;
    }
    public function processItemBeforeId(&$item,$params=null)
    {
        //if item is not configurable, nothing to do
        if($item["type"]!=="configurable")
        {
            return true;
        }       
        if($this->_use_defaultopc || ($item["options_container"]!="container1" && $item["options_container"]!="container2"))
        {
            $item["options_container"]="container2";
        }
        //reset option price info
        $this->_optpriceinfo=array();
        if(isset($item["super_attribute_pricing"]))
        {
            $this->_optpriceinfo=$this->buildSAPTable($item["super_attribute_pricing"]);
            unset($item["super_attribute_pricing"]);
        }
        return true;
    }
    
    public function getMatchMode($item)
    {
        $matchmode="auto";
        if($this->getParam('CFGR:nolink',0))
        {
            $matchmode="none";
            
        }
        else
        {
            if($this->getParam("CFGR:simplesbeforeconf")==1)
            {
                $matchmode="cursimples";
            }
            if(isset($item["simples_skus"]) && trim($item["simples_skus"])!="")
            {
                $matchmode="fixed";
            }
        }
        return $matchmode;
    }


    public function forAttr($attr,$curr_arr_index,$curr,$all_conf_attr,$curr_conf){ 

       
                for($x=0;$x< count($attr[$curr_arr_index]);$x++){  
                      
                    if( $curr_arr_index >= count($attr)-1){  
                        $simple_sku_temp = array();  
                        $simple_name_temp = array();  
                        $temp_simp = array();
                        $temp_simp = $this->simple_data_temp['simple_template'];
                        $curr[$curr_arr_index] = $attr[$curr_arr_index][$x]; 
                        $curr_conf[$curr_arr_index] = $all_conf_attr[$curr_arr_index]; 
                        foreach($curr as $k=>$y){  
                            $simple_name_temp[] = $y;  
                            $simple_sku_is_ok = $this->_skumaps[$y]; 
                            if($simple_sku_is_ok){
                                $simple_sku_temp[] = $this->_skumaps[$y];
                            }else{
                                $this->log("#sku map not map:" . $y . "warning");
                                $simple_sku_temp = false;
                                break;
                            }
                            
                              
                            
                            $temp_simp[$curr_conf[$k]] = $y;
                        }  
                        if($simple_sku_temp != false){
                            $temp_simp['sku'] = $this->simple_data_temp['conf_sku'] ."-".implode("-", $simple_sku_temp);
                            $temp_simp['name'] = $this->simple_data_temp['conf_name'] ."-".implode("-", $simple_name_temp);
                            $this->simple_data_temp['simple'][] = $temp_simp;

                        }else{
                            $this->log("#sku map is missing, sku:  " . $temp_simp['sku'] ,"warning");
                        }
                        
 // file_put_contents("hhjc8.txt" ,  json_encode($temp_simp) ,FILE_APPEND);
                        // foreach($temp_simp as $kkk=>$yyy){  
                        //     // $temp_simp[$yy] = $y;  
                        //     file_put_contents("hhjc8.txt" ,  "\n".$kkk."=>".$yyy ,FILE_APPEND);
                        // } 
                        
                            // $("#result").html($("#result").html() + "<br/>" +str);  
                          
                    }else{  
                        $curr[$curr_arr_index] = $attr[$curr_arr_index][$x];  
                        $curr_conf[$curr_arr_index] = $all_conf_attr[$curr_arr_index];  
                          // file_put_contents("hhjc8.txt" ,  "\nall_conf_attr:" . $all_conf_attr[$curr_arr_index],FILE_APPEND);
                        $this->forAttr($attr,$curr_arr_index+1,$curr,$all_conf_attr,$curr_conf);  
                    }  
                          
                 }  
                 $curr=null;  
          //      file_put_contents("hhjc8.txt" , $simple_item['sku'],FILE_APPEND);
                    // file_put_contents("hhjc8.txt" , "\n",FILE_APPEND);
                    // exit();

    }
    
    public function processItemAfterId(&$item,$params=null)
    {
        
        
         // file_put_contents("hhjc8.txt" , "start\n",FILE_APPEND);
        //if item is not configurable, nothing to do
        if($item["type"]!=="configurable")
        {
            if($this->getParam("CFGR:simplesbeforeconf")==1)
            {
                $this->_currentsimples[]=$item["sku"];
            }
            return true;
        }       
        //check for explicit configurable attributes
        if(isset($item["configurable_attributes"]))
        {
            $confopts=explode(",",$item["configurable_attributes"]);
            for($i=0;$i<count($confopts);$i++)
            {
                $confopts[$i]=trim($confopts[$i]);
            }
        }
        //if not found, try to deduce them
        else
        {
            $asconfopts=$this->getConfigurableOptsFromAsId($params["asid"]);
            //limit configurable options to ones presents & defined in item
            $confopts=array();
            foreach($asconfopts as $confopt)
            {
                if(in_array($confopt,array_keys($item)) && trim($item[$confopt])!="")
                {
                    $confopts[]=$confopt;
                }
            }
            unset($asconfotps);
        }
        //if no configurable attributes, nothing to do
        
        if(count($confopts)==0)
        {
            $this->log("No configurable attributes found for configurable sku: ".$item["sku"]." cannot link simples.","warning");
            return true;
        }
// file_put_contents("hhjc8.txt" , "it run h:" . count($confopts),FILE_APPEND);
// exit();
        //set product to have options & required
        $tname=$this->tablename('catalog_product_entity');
        $sql="UPDATE $tname SET has_options=1,required_options=1 WHERE entity_id=?";
        $this->update($sql,$params["product_id"]);
//      //matching mode
//      //if associated skus 
//      
//      $matchmode=$this->getMatchMode($item);


        //check if item has exising options
        $pid=$params["product_id"];
        $cpsa=$this->tablename("catalog_product_super_attribute");
        $cpsal=$this->tablename("catalog_product_super_attribute_label");
                    
        //process configurable options
        $ins_sa=array();
        $data_sa=array();
        $ins_sal=array();
        $data_sal=array();
        $idx=0;
        foreach($confopts as $confopt)
        {
            
            $attrinfo=$this->getAttrInfo($confopt);
            $attrid=$attrinfo["attribute_id"];
            $psaid=NULL;

            //try to get psaid for attribute
            $sql="SELECT product_super_attribute_id as psaid FROM `$cpsa` WHERE product_id=? AND attribute_id=?";
            $psaid=$this->selectOne($sql,array($pid,$attrid),"psaid");          
            //if no entry found, create one
            if($psaid==NULL)
            {
                $sql="INSERT INTO `$cpsa` (`product_id`,`attribute_id`,`position`) VALUES (?,?,?)";
                //inserting new options
                $psaid=$this->insert($sql,array($pid,$attrid,$idx));    
            }
            
            
            //for all stores defined for the item
            $sids=$this->getItemStoreIds($item,0);
            $data=array();
            $ins=array();
            foreach($sids as $sid)
            {
                $data[]=$psaid;
                $data[]=$sid;
                $data[] = $attrinfo['frontend_label'];
                $ins[]="(?,?,1,?)";
            }
            if(count($ins)>0)
            {
                //insert/update attribute value for association
                $sql="INSERT INTO `$cpsal` (`product_super_attribute_id`,`store_id`,`use_default`,`value`) VALUES ".implode(",",$ins).
                "ON DUPLICATE KEY UPDATE value=VALUES(`value`)";
                $this->insert($sql,$data);
            }
            //if we have price info for this attribute
            if(isset($this->_optpriceinfo[$confopt]))
            {
                $cpsap=$this->tablename("catalog_product_super_attribute_pricing");
                $wsids=$this->getItemWebsites($item);
                //if admin set as store, website force to 0
                if(in_array(0,$sids))
                {
                    $wsids=array(0);
                }
                $data=array();
                $ins=array();

                foreach($this->_optpriceinfo[$confopt] as $opdef)
                {
                    //if optpriceinfo has no is_percent, force to 0
                    $opinf=explode(":",$opdef);
                    $optids=$this->getOptionIds($attrid,0,explode("//",$opinf[0]));
                    foreach($optids as $optid)
                    {
                        //generate price info for each given website
                        foreach($wsids as $wsid)
                        {
                            if(count($opinf)<3)
                            {
                                $opinf[]=0;
                            }
                
                            $data[]=$psaid;
                            $data[]=$optid;
                            $data[]=$opinf[1];
                            $data[]=$opinf[2];
                            $data[]=$wsid;
                            $ins[]="(?,?,?,?,?)";   
                        }
                    }
                }
            
                $sql="INSERT INTO $cpsap (`product_super_attribute_id`,`value_index`,`pricing_value`,`is_percent`,`website_id`) VALUES ".implode(",",$ins).
                " ON DUPLICATE KEY UPDATE pricing_value=VALUES(pricing_value),is_percent=VALUES(is_percent)";
                $this->insert($sql,$data);
                unset($data);
            }
            $idx++;
        }



        $this->simple_data_temp = array();
        $this->simple_data_temp['conf_sku'] = $item["sku"];
        $this->simple_data_temp['conf_name'] = $item["name"];
        $this->simple_data_temp['simple'] = array();
        $this->simple_data_temp['simple_template'] = array();


                
                ////DH_MOD:重写这一部分内容, 让导入可以自动创建simple product
                if($confopts > 0){
                    
                $cline = $this->getCurrentImportRow();
                
               
                
                // if(count($confopts) == 1 ){
                    
                //     if($confopts[0] == 'dress_color'){
                //         $this->log("#dhcolor is missing, sku:  " . $item['sku'] . " ,Line: $cline","warning");
                //         return false;
                //     }
                //     if($confopts[0] == 'dress_size' ){
                //         $this->log("#dhlength is missing, sku:  " . $item['sku'] . " ,Line: $cline","warning");
                //         return false;
                //     }
                    
                // }
                
                //读取sku map 表, 这个表是由订单系统的 product_net_name 导出的, 导出时可以用这句bash 命令
                /**
                 * #!/bin/bash
                   FILE_NAME=/var/www/newuk/magmi/state/skumap.csv
                   ssh -p 1863 dh-order 'mysql -uroot -ss -e "select net_name,sku from mallerp.product_net_name group by sku"|sed "s/\t/\",\"/g;s/^/\"/;s/$/\"/;s/\n//g"' > $FILE_NAME
                 */
                if(!isset($this->_skumaps)){
                    $skumap_csv = Magmi_StateManager::getStateDir().DS.'skumap_yifu.csv';
                    if(file_exists($skumap_csv))
                    {
                            $this->_skumaps = array();
                            $fp = fopen($skumap_csv, 'r');
                            while ($data = fgetcsv($fp)){
                                $this->_skumaps[trim($data[0])] = trim($data[1]); //sku 与文本对
                            }
                            fclose($fp);
                    }
                }
                
                $price = floatval($item['price']);
                $_special_price = floatval($item['special_price']);
                
                $simple_skus = array();
                $simple_products = array();
                $error_attribute = array();
               
                if(count($confopts) == 2 ){
                    //sku = parent sku- size_code - color_code
                    $simple_item = array();
                    $simple_item = $item;
                    $simple_item['name'] = $item['name'] ;
                    $simple_item['type'] = 'simple';
                    $simple_item['product_type_id'] = 'simple';
                    $simple_item['psku'] = $item['sku'];
                    $simple_item['has_options'] = 0;
                    $simple_item['visibility'] = 1;
                    
                    unset($simple_item['categories']);
                    unset($simple_item['url_key']);
                    unset($simple_item['tier_price:_all']);
                    unset($simple_item['image']);
                    unset($simple_item['small_image']);
                    unset($simple_item['thumbnail']);
                    unset($simple_item['gallery']);
                    unset($simple_item['url_key']);
                    unset($simple_item['configurable_attributes']);
                    unset($simple_item['category_ids']);
                    $simple_item['category_ids'] = null;
                    $simple_item['categories'] = null;
                    $simple_item['price'] = $price;
                    $simple_item['special_price'] = $_special_price; 
                    $simple_sku = array();
                    $simple_sku[] = $item['sku'];
                    $simple_name = array();
                    $simple_name[] = $simple_item['name'];

                    $all_attr = array();
                    $all_conf_attr = array();

                    $this->simple_data_temp['simple_template'] =$simple_item;

                    foreach($confopts as $conf_attr){  
                        $all_attr[] =   $this->_optpriceinfo[$conf_attr] ;
                        $all_conf_attr[] = $conf_attr;
                       
                    }
                    $this->forAttr($all_attr,0,array(),$all_conf_attr,array());
                    $simple_products = $this->simple_data_temp['simple'];
                     // file_put_contents("hhjc8.txt" ,  json_encode($simple_products) ,FILE_APPEND);
                }
               
                
     
                
                $simple_product_skus = $this->walkAllSimpleProducts($simple_products);
                
                // if(count($simple_product_skus)>0){
                //     $item['simples_skus'] = implode(',', $simple_product_skus);
                // }
                
                }//end if $confopts > 0
                //DH_END end auto general simple product
        // $this->log(count($confopts));
        unset($confopts);
                
                //matching mode
        //if associated skus 
        
        $matchmode=$this->getMatchMode($item);
                
        switch($matchmode)
        {
            case "none":
                break;
            case "auto":
                //destroy old associations
                $this->autoLink($pid);
                $this->updSimpleVisibility($pid);
                break;
            case "cursimples":
                $this->fixedLink($pid,$this->_currentsimples);
                $this->updSimpleVisibility($pid);
                
                break;
            case "fixed":
                $sskus=explode(",",$item["simples_skus"]);
                trimarray($sskus);
                $this->fixedLink($pid,$sskus);
                $this->updSimpleVisibility($pid);
                unset($item["simples_skus"]);
                break;
            default:
                break;
        }
        //always clear current simples
        if(count($this->_currentsimples)>0)
        {
            unset($this->_currentsimples);
            $this->_currentsimples=array();
        }
        return true;
    }
    
    
    public function processColumnList(&$cols,$params=null)
    {
        if(!in_array("options_container",$cols))
        {
            $cols=array_unique(array_merge($cols,array("options_container")));
            $this->_use_defaultopc=true;
            $this->log("no options_container set, defaulting to :Block after product info","startup");
        }
    }
    
    public function getPluginParamNames()
    {
        return array("CFGR:simplesbeforeconf","CFGR:updsimplevis","CFGR:nolink");
    }
    
    static public function getCategory()
    {
        return "Product Type Import";
    }
          
}