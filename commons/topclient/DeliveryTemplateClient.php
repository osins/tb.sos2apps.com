<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DeliveryTemplateClient
 *
 * @author richar.wang
 */
class DeliveryTemplateClient extends TBClient {
    public function __construct($user) {
        parent::__construct($user);
    }
    
    public function areas(){
        $req = new AreasGetRequest;
        $req->setFields("id,type,name,parent_id,zip");
        $resp = $this->execute($req);
        foreach($resp->areas->area as $a){
            $value = array(
                "id"=>$a->id,
                "name"=>$a->name,
                "type"=>$a->type,
                "parent_id"=>$a->parent_id,
                "zip"=>$a->zip
            );
            
            $record = AreaModel::first(array('id'=> $a->id));
            if ($record) {
                $result = $record->update_attributes($value);
            } else {
                $result = AreaModel::create($value);
            }
        }
    }
    
    public function templates() {
        $req = new DeliveryTemplatesGetRequest;
        $req->setFields("template_id,template_name,created,modified,supports,assumer,valuation,query_express,query_ems,query_cod,query_post");
        $resp = $this->client->execute($req, $this->session);
        foreach($resp->delivery_templates->delivery_template as $t){
            Console::vardump($t);echo "<br>";
        }
    }
    
    public function getTemplate($templateId){
        $req = new DeliveryTemplateGetRequest;
        $req->setTemplateIds($templateId);
        $req->setFields("template_id,template_name,created,modified,supports,assumer,valuation,query_express,query_ems,query_cod,query_post,address,consign_area_id,query_furniture,query_bzsd,query_wlb");
        $resp = $this->execute($req);
        foreach($resp->delivery_templates->delivery_template->fee_list->top_fee as $t){
            $areaids = explode(',',$t->destination);
            foreach ($areaids as $id){
                $area = AreaModel::first(array('id'=> $id));
                $name = $area ? $area->name : "未知";
                $value = array(
                    "area_id"=>(integer)$id,
                    "area_name"=>$name,
                    "service_type"=>(string)$t->service_type,
                    "start_fee"=>(double)$t->start_fee,
                    "add_fee"=>(double)$t->add_fee,
                    "start_standard"=>(integer)$t->start_standard,
                    "add_standard"=>(integer)$t->add_standard,
                    "user_id"=>$this->userId
                );
                Console::vardump($id);
                $record = DeliveryFeeModel::first(array('area_id'=>$id,'service_type'=>$value['service_type']));
                if($record){
                    $result = $record->update_attributes($value);Console::vardump($record);
                }else{
                    $result = DeliveryFeeModel::create($value);
                }
            }
        }
    }
}

?>
