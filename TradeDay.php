<?php
include("commons/Global.php");
include("commons/TradeConfig.php");
include("commons/domain/DeliveryFeeDM.php");
include("commons/topclient/TradeClient.php");

$qYear = isset($_GET['y']) ? $_GET['y'] : date("Y");
$qMonth = str_pad((isset($_GET['m']) ? $_GET['m'] : date("m")),2,'0',STR_PAD_LEFT);
$qDay = str_pad((isset($_GET['d']) ? $_GET['d'] : date("d")),2,'0',STR_PAD_LEFT);

$startDay = "$qYear-$qMonth-$qDay 00:00:00";
$endDay = "$qYear-$qMonth-$qDay 23:59:59";

$prevDay = date("d",strtotime($startDay)-86400);
$nextDay = date("d",strtotime($startDay)+86400);

$trade = new TradeClinet($currentUser);
$trade->syncByStartAndEnd($startDay,$endDay);
    
$forgetNick = explode(",","fudequandehua");

$trades = array();
    
$total = array();
$total['payment'] = 0;
$total['profits'] = 0;

$userId = $currentUser->getUserId();
$records = TradeModel::find('all', array('conditions' => array('user_id=? and created>=? and created<=?', $userId, strtotime($startDay), strtotime($endDay)),'order'=>'created DESC' ));
$tradeIds = array();
$trades = array();
$dayStatistics = array('profits'=>0,'payment'=>0);
$address = array();
foreach($records as $trade){
    if (!($trade->seller_flag < 5 && !in_array($trade->status, $TRADE_FORGET_STATUS) && !in_array($trade->status, $forgetNick))) { 
        continue;
    }
    
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
    
    $tradeIds[] = $trade->tid;
    
    $trades[$trade->tid]['tid'] = $trade->tid;
    $trades[$trade->tid]['seller_flag'] = $trade->seller_flag>0 ? $trade->seller_flag : 0;
    $trades[$trade->tid]['buyer_nick'] = $trade->buyer_nick;
    $trades[$trade->tid]['receiver_city'] = $trade->receiver_city;
    $trades[$trade->tid]['post_fee'] = $trade->post_fee;
    $trades[$trade->tid]['post_fee2'] = $postfee;
    $trades[$trade->tid]['payment'] = $trade->payment;
    $trades[$trade->tid]['total_fee'] = $trade->total_fee;
    $trades[$trade->tid]['created_dt'] = date('Y-m-d H:i:s',$trade->created);
    $trades[$trade->tid]['pay_time_dt'] = date('Y-m-d H:i:s',$trade->pay_time);
    $trades[$trade->tid]['profits'] = $profits;
    $trades[$trade->tid]['status_name'] = $TRADE_STATUS_DICT[trim($trade->status)];
    $trades[$trade->tid]['orders'] = array();
    
    if ($trade->seller_flag < 5 && !in_array($trade->status, $TRADE_FORGET_STATUS) && !in_array($trade->status, $forgetNick)) { 
        $dayStatistics['profits'] = $dayStatistics['profits'] + $profits;
        $dayStatistics['payment'] = $dayStatistics['payment'] + $trade->payment; 
    }
}

if(!empty($tradeIds)){
    $orders = OrderModel::find('all', array('conditions' => array('tid in (?) ', $tradeIds)));
    foreach($orders as $order){
        $trades[$order->tid]["orders"][] = $order;    
    }
}

$page->assign('tradeCount',count($trades));
$page->assign('total',$total);
$page->assign('tradeStatusDict',$TRADE_STATUS_DICT);
$page->assign('trades',$trades);
$page->assign('params',array('y'=>$qYear,'m'=>$qMonth,'d'=>$qDay));
$page->assign('dayStatistics',$dayStatistics);
$page->assign('nextDay',$nextDay);
$page->assign('prevDay',$prevDay);

$page->loadTpl('TradeDay.tpl');
?>

