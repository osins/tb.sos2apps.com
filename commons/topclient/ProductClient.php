<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProductClient
 *
 * @author richar.wang
 */
class ProductClient extends TBClient{
    public function __construct($user) {
        parent::__construct($user);
    }
    
    public function get($productId){        
        $req = new ProductGetRequest;
        $req->setFields("product_id,outer_id,sale_num");
        $req->setProductId($productId);
        $resp = $this->execute($req);
        
        Console::writeln($productId);
        Console::vardump($resp);
    }
    
    public function getList(){
        $req = new ProductsGetRequest;
        $req->setFields("product_id,tsc,cat_name,name");
        $req->setNick("雷蒙饰品");
        $req->setPageNo(1);
        $req->setPageSize(40);
        $resp = $this->execute($req);
        Console::vardump($resp);
    }
}

?>
