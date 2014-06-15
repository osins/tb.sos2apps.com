<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AreaDM
 *
 * @author richar.wang
 */
class AreaDM {
    public static function getAreaByName($name){
        return AreaModel::first(array('name'=>$name));
    }
    
    public static function getProvinceByCityName($name){
        $area = AreaModel::first(array('name'=>$name));
        if(empty($area) || $area->type==2){
            return $area;
        }
        
        return self::getProvinceByCityId($area->parent_id);
    }
    
    public static function getProvinceByCityId($id){
        $area = AreaModel::first(array('id'=>$id));
        if(empty($area) || $area->type==2){
            return $area;
        }
        
        return self::getProvinceByCityId($area->parent_id);
    }
}

?>
