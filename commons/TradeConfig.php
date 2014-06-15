<?php
$TRADE_FORGET_STATUS = explode(",","TRADE_CLOSED_BY_TAOBAO,WAIT_BUYER_PAY,TRADE_CLOSED");
$TRADE_STATUS_DICT = array(
        "TRADE_NO_CREATE_PAY" => "没有创建支付宝交易",
        "WAIT_BUYER_PAY" => "等待买家付款",
        "WAIT_SELLER_SEND_GOODS" => "等待卖家发货",
        "SELLER_CONSIGNED_PART" => "卖家部分发货",
        "WAIT_BUYER_CONFIRM_GOODS" => "等待买家确认收货",
        "TRADE_BUYER_SIGNED" => "买家已签收,货到付款专用",
        "TRADE_FINISHED" => "交易成功",
        "TRADE_CLOSED" => "交易关闭",
        "TRADE_CLOSED_BY_TAOBAO" => "交易被淘宝关闭",
);
?>
