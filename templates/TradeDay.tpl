<h3>当前位置：{$params.y}年{$params.m}月{$params.d}日成交的订单,您还可以查看: <a href="StatMonth.php?y={$params.y}&m={$params.m}">本月</a> | <a href="TradeDay.php?y={$params.y}&m={$params.m}&d={$prevDay}">前一天</a> | <a href="TradeDay.php?y={$params.y}&m={$params.m}&d={$nextDay}">下一天</a></h3>
<h3 style="margin-bottom: 10px;">{$params.y}年{$params.m}月{$params.d}日 交易成功:{$tradeCount}单 总销售额:{$dayStatistics.payment} 合计利润:{$dayStatistics.profits}</h3>
    {foreach from=$trades item=trade}
        <div style="width:800px;border: 1px #CCCCCC solid; margin-bottom: 5px;padding: 5px; ">
            <div style="width:800px; clear: both; height: 55px;">
                <div style="float:left;width:60px;height:55px; text-align: center; background-color: #F5F5F5;">
                    <span style="color:red;font-size: 16px;font-family: 黑体; margin-top: 5px; display: block;">利润<br>{$trade.profits}</span>
                </div>
                <div style="width:725px;height:45px; float: left; margin-left: 5px; background-color: #F5F5F5;padding: 5px;">
                    <div>
                        <span class="ui-li-count"><img src="http://a.tbcdn.cn/sys/common/icon/trade/op_memo_{$trade.seller_flag}.png"></span>
                        订单号：<a href="http://trade.taobao.com/trade/detail/trade_item_detail.htm?bizOrderId={$trade.tid}" target="_blank" class="ui-link-inherit">{$trade.tid}</a> 
                        拍下时间:{$trade.created_dt|date_format:'%H:%M:%S'} | 付款时间:{$trade.pay_time_dt|date_format:'%H:%M:%S'} | 状态:{$trade.status_name} 
                    </div>
                    <div>买家付邮费：{$trade.post_fee} | 实际邮费:{$trade.post_fee2} | 买家付款:{$trade.payment} | 成本:{$trade.total_fee*0.25}</div>
                    <div>{$trade.buyer_nick} | {$trade.receiver_city}</div>                
                </div>
            </div>
            <div style="width:790px; margin-top: 5px; border: 1px #CCCCCC solid; padding: 5px;">
                {foreach from=$trade.orders item=order}
                    <div style="width:780;height:80px; padding: 5px; background-color: #F5F5F5;">
                        <img src="{$order->pic_path}" style="width:80px;height: 80px;float:left;"/>
                        <div style="float:left;width:720;margin-left: 5px;">
                            <strong style="color:darkblue;"><a href="http://item.taobao.com/item.htm?id={$order->num_iid}" target="_blank">{$order->title}</a></strong><br>
                            {if $order->sku_properties_name}
                                <strong style="color:firebrick;">{$order->sku_properties_name}</strong><br>
                            {/if}
                            数量:{$order->num},原价:{$order->price},实付:{$order->payment},成本:{$order->price*.25},利润:{$order->payment-$order->price*.25}</div>
                    </div>
                {/foreach}
            </div>
        </div>
    {/foreach}
