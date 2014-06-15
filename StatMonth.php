<?php
include("commons/Global.php");
include("commons/TradeConfig.php");
include("commons/domain/DeliveryFeeDM.php");

$qYear = isset($_GET['y']) ? $_GET['y'] : date("Y");
if(isset($_GET['m'])){
    $qMonth = str_pad($_GET['m'],2,'0',STR_PAD_LEFT);
}else{
    $qMonth = str_pad(date('m'),2,'0',STR_PAD_LEFT);    
}
$startDay = "$qYear-$qMonth-01 00:00:00";
$endDay = "$qYear-$qMonth-". str_pad(date("t",  strtotime($startDay)), 2, '0', STR_PAD_LEFT) ." 00:00:00";
      
$c = new TopClient;
$c->appkey = TOP_APPKEY;
$c->secretKey = TOP_SECRET;

$forgetNick = explode(",","fudequandehua");
$forgetStatus = explode(",","TRADE_CLOSED_BY_TAOBAO,WAIT_BUYER_PAY");

$getCount = 0;
$pageIndex=1;
$sQuery = true;
$dayStatistics = array();
$monthStatistics = array('finished'=>0,'profits'=>0,'payment'=>0,'closed'=>0,'closed_profits'=>0,'closed_payment'=>0,'post_fee'=>0,'costs'=>0,'closed_costs'=>0);
$userId = $currentUser->getUserId();
$conditions = array('conditions' => array('user_id=? and created>=? and created<=?', $userId, strtotime($startDay), strtotime($endDay)));
$records = TradeModel::find('all',$conditions);
if(!empty($records)){
    $address = array();
    foreach ($records as $trade) {  
        $y = date("Y", $trade->created);
        $m = date("n", $trade->created);
        $d = date("d", $trade->created);
                
        $gift = 5;  /*赠品*/
        $cashback = 10; /*好评返现*/        
        if($trade->created>strtotime("2014-05-28 00:00:00")){
            $cashback = 5;
        }
        
        /* 销售提成 */
        $commission = $trade->seller_flag == 3 || $trade->seller_flag == 4 ? 5 : 0;
        
        $postfeeObj = DeliveryFeeDM::getFee($userId, $trade->shipping_type, $trade->receiver_city);        
        $postfee = $postfeeObj ? $postfeeObj->start_fee : 0;
        
        /*额外成本*/
        $costs = $gift + $cashback + $commission;
        $profits = $trade->payment - $postfee - ($trade->total_fee * 0.25);
        
        $addressKey = $y.$m.$d.$trade->receiver_name.$trade->receiver_state.$trade->receiver_address.$trade->receiver_zip.$trade->receiver_mobile.$trade->receiver_phone;
        if(!key_exists($addressKey, $address)){
            if($trade->seller_flag < 5 && !in_array($trade->status, $TRADE_FORGET_STATUS) && !in_array($trade->status, $forgetNick)){
                $address[$addressKey]=true;
            }
            
            $profits = $trade->payment - ($trade->total_fee * 0.25) - $costs;
        }
        
        if(!key_exists($y,$dayStatistics)) $dayStatistics[$y]=array();
        if(!key_exists($m,$dayStatistics[$y])) $dayStatistics[$y][$m]=array();
        if(!key_exists($d,$dayStatistics[$y][$m])) $dayStatistics[$y][$m][$d]=array('profits'=>0,'post_fee'=>0,'payment'=>0,'finished'=>0,'closed'=>0,'closed_profits'=>0,'closed_payment'=>0,);

        if ($trade->seller_flag < 5 && !in_array($trade->status, $TRADE_FORGET_STATUS) && !in_array($trade->status, $forgetNick)) {            
            $dayStatistics[$y][$m][$d]['profits'] = $dayStatistics[$y][$m][$d]['profits'] + $profits;
            $dayStatistics[$y][$m][$d]['post_fee'] = $dayStatistics[$y][$m][$d]['post_fee'] + $postfee;
            $dayStatistics[$y][$m][$d]['payment'] = $dayStatistics[$y][$m][$d]['payment'] + $trade->payment;
            $dayStatistics[$y][$m][$d]['finished'] ++;
            $monthStatistics['finished'] ++;
            $monthStatistics['profits'] = $monthStatistics['profits'] + $profits;
            $monthStatistics['payment'] = $monthStatistics['payment'] + $trade->payment; 
            $monthStatistics['post_fee'] = $monthStatistics['post_fee'] + $postfee;
            $monthStatistics['costs'] = $monthStatistics['costs'] + $costs;
        }  elseif($trade->seller_flag < 5 && in_array($trade->status,explode(',','TRADE_CLOSED,TRADE_CLOSED_BY_TAOBAO')) && !in_array($trade->status, $forgetNick)) {
            $dayStatistics[$y][$m][$d]['closed'] ++;
            $dayStatistics[$y][$m][$d]['closed_profits']=$dayStatistics[$y][$m][$d]['closed_profits']+$profits;
            $dayStatistics[$y][$m][$d]['closed_payment']=$dayStatistics[$y][$m][$d]['closed_payment']+$trade->payment;
            $monthStatistics['closed'] ++;
            $monthStatistics['closed_profits'] = $monthStatistics['closed_profits'] + $profits;
            $monthStatistics['closed_payment'] = $monthStatistics['closed_payment'] + $trade->payment;  ;
            $monthStatistics['closed_costs'] = $monthStatistics['closed_costs'] + $costs;       
        }        
    }
}

$page->assign('qYear',$qYear);
$page->assign('qMonth',$qMonth);
$page->assign('dayStatistics',$dayStatistics);
$page->assign('monthStatistics',$monthStatistics);
$page->display('Header.tpl');

if(!empty($dayStatistics)){
    $page->display('StatisticsMonth.tpl');
}else{
    $page->display('StatisticsMonth_NoData.tpl');    
}

$page->display('Footer.tpl');
?>
