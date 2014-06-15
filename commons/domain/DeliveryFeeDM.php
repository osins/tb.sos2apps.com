<?php
if(!class_exists("AreaDM")){
    include("AreaDM.php");
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DeliveryFeeDM
 *
 * @author richar.wang
 */
class DeliveryFeeDM extends Domain{    
    public function getAll($userId){        
        $result = array();
        $deliveryFees = DeliveryFeeModel::all(array("user_id"=>$userId));
        foreach($deliveryFees as $f){
            $result[$f->service_type][$f->area_id] = $f;
        }
        
        return $result;
    }
    
    public function getFee($userId, $serviceType, $name){
        $fees = self::getAll($userId);
        if(!key_exists($serviceType, $fees)){
            return null;
        }  
        
        $service = $fees[$serviceType];
        $area = AreaDM::getAreaByName($name);   
        if(empty($area)){
            return $service[1];
        }
        
        if(key_exists($area->id,$service)){
            return $service[$area->id];
        }
        
        $province = AreaDM::getProvinceByCityId($area->parent_id);
        if(empty($province)){
            return null;
        }
        
        if(key_exists($province->id,$service)){
            return $service[$province->id];
        }
        
        return $service[1];
    }
}

?>
