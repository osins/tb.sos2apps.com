<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Trade
 *
 * @author richar.wang
 */
class TradeClinet extends TBClient{
    public function __construct($user) {
        parent::__construct($user);
    }

    public function syncByStartAndEnd($qStartDay=null, $qEndDay=null) {
        $this->sync(null, null, $qStartDay, $qEndDay);
    }
    
    public function sync($qYear=null, $qMonth=null, $qStartDay=null, $qEndDay=null) {
        $pageIndex = 1;
        $trades = array();
        $sQuery = true;
        $getCount = 0;

        if($qStartDay && $qEndDay){   
            $startDay = $qStartDay;
            $endDay = $qEndDay;
        }else{
            if($qYear && $qMonth){            
                $startDay = $qStartDay ? "$qYear-".str_pad($qMonth, 2, '0', STR_PAD_LEFT)."-" . str_pad($qStartDay, 2, '0', STR_PAD_LEFT) . "  00:00:00" : "$qYear-".str_pad($qMonth, 2, '0', STR_PAD_LEFT)."-01 00:00:00";
                $endDay = $qEndDay ? "$qYear-$qMonth-" . str_pad($qEndDay, 2, '0', STR_PAD_LEFT) . " 23:59:59" : "$qYear-".str_pad($qMonth, 2, '0', STR_PAD_LEFT)."-" . str_pad(date("t", strtotime($startDay)), 2, '0', STR_PAD_LEFT) . " 23:59:59";            
            }else{
                $qYear = '20'.date('y');
                $month = date('m');
                $qStartMonth = str_pad($month-1, 2, '0', STR_PAD_LEFT);
                $qMonth = str_pad(date('m'), 2, '0', STR_PAD_LEFT);
                $qDay = date('t');

                $startDay = "$qYear-$qStartMonth-01 00:00:00";
                $endDay = "$qYear-$qMonth-$qDay 23:59:59";
            }
        }
        
        $req = new TradesSoldGetRequest;
        $req->setFields("seller_nick, buyer_nick, title, type, created, tid, area_id, orders, seller_rate,seller_can_rate, buyer_rate,can_rate, status, payment, discount_fee, adjust_fee, post_fee, total_fee, pay_time, end_time, modified, consign_time, buyer_obtain_point_fee, point_fee, real_point_fee, received_payment, pic_path, num_iid, num, price, cod_fee, cod_status, shipping_type, receiver_name, receiver_state, receiver_city, receiver_district, receiver_address, receiver_zip, receiver_mobile, receiver_phone,seller_flag,alipay_id,alipay_no,is_lgtype,is_force_wlb,is_brand_sale,buyer_area,has_buyer_message, credit_card_fee, lg_aging_type, lg_aging, step_trade_status,step_paid_fee,mark_desc,has_yfx,yfx_fee,yfx_id,yfx_type,trade_source,send_time,is_daixiao,is_wt,is_part_consign");        
        
        $req->setStartCreated($startDay);
        $req->setEndCreated($endDay);
            
        $req->setStatus("*");
        $req->setUseHasNext("true");
        $req->setPageSize(100);
        
        $tradeFields = explode(",", "tid,user_id,seller_nick,payment,post_fee,snapshot_url,pic_path,seller_rate,buyer_alipay_no,receiver_name,receiver_state,receiver_address,receiver_zip,receiver_mobile,receiver_phone,consign_time,seller_alipay_no,seller_mobile,seller_phone,seller_name,seller_email,available_confirm_fee,received_payment,timeout_action_time,promotion,promotion_details,num,num_iid,status,title,type,price,seller_cod_fee,discount_fee,point_fee,has_post_fee,total_fee,is_lgtype,is_brand_sale,is_force_wlb,lg_aging,lg_aging_type,created,pay_time,modified,end_time,has_buyer_message,buyer_message,alipay_id,alipay_no,seller_memo,seller_flag,has_yfx,yfx_fee,yfx_id,yfx_type,area_id,credit_card_fee,step_trade_status,step_paid_fee,mark_desc,shipping_type,buyer_cod_fee,express_agency_fee,adjust_fee,buyer_obtain_point_fee,cod_fee,trade_from,alipay_warn_msg,cod_status,trade_memo,buyer_nick,buyer_rate,trade_source,seller_can_rate,is_part_consign,real_point_fee,receiver_city,receiver_district,arrive_interval,arrive_cut_time,consign_interval,async_modified,is_wt");
        $orderFields = explode(",", "id,oid,user_id,item_meal_name,pic_path,seller_nick,buyer_nick,refund_status,outer_iid,buyer_rate,seller_rate,seller_type,cid,status,title,price,num_iid,item_meal_id,sku_id,num,outer_sku_id,total_fee,payment,discount_fee,adjust_fee,modified,sku_properties_name,refund_id,end_time,consign_time,shipping_type,bind_oid,logistics_company,invoice_no,is_daixiao,divide_order_fee,part_mjz_discount,is_www");
        while ($sQuery && $getCount < 30) {
            $req->setPageNo($pageIndex);
            $resp = $this->client->execute($req, $this->session);
            if(!empty($resp->trades->trade)){
                foreach ($resp->trades->trade as $node) {
                    $temp = (array) $node;
                    $value = array();

                    foreach ($tradeFields as $k) {
                        if(array_key_exists(trim($k), $temp)){
                            $value[trim($k)] = $temp[trim($k)];
                         }
                    }

                    $value["user_id"] = $this->userId;
                    $dateFields = explode(",", "consign_time,created,pay_time,modified,end_time,async_modified");
                    foreach($dateFields as $f){
                        if(array_key_exists($f, $value)){
                            $value[$f] = strtotime($value[$f]);
                        }
                    }

                    $trade = TradeModel::first(array('tid'=> $node->tid, 'user_id' => $this->userId));
                    if ($trade) {
                        $result = $trade->update_attributes($value);
                    } else {
                        $result = TradeModel::create($value);
                    }

                    foreach ($node->orders->order as $o){
                        $temp = (array) $o;
                        $value = array();

                        foreach ($orderFields as $k) {
                             if(array_key_exists(trim($k), $temp)){
                                $value[trim($k)] = $temp[trim($k)];
                             }
                        }
                        
                        $value["user_id"] = (int)$this->userId;
                        $value["tid"] = (int)$node->tid;
                        
                        $dateFields = explode(",", "modified,end_time,consign_time");
                        foreach($dateFields as $f){
                            if(array_key_exists($f, $value)){
                                $value[$f] = strtotime($value[$f]);
                            }
                        }
                        
                        $options = array('user_id'=>(int)$this->userId, 'tid'=>(int)$node->tid, 'oid'=>(int)$o->oid);
                        $res = OrderModel::first($options);
                        if($res){
                            $result = $res->update_attributes($value);
                        }else{                        
                            $result = OrderModel::create($value);
                        }
                    }
                }
            }
            
            $sQuery = !empty($resp->trades->trade) && $resp->has_next;
            $getCount++;
            $pageIndex++;
        }

        return $trades;
    }

}

?>
