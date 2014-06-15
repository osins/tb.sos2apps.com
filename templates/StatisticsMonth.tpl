<div class="body">
        <table class="list">
            <tr style="background-color: #2D7ECA;"><td colspan="10"><a href="StatMonth.php?y={$qYear}&m={$qMonth-1}" style="color:#eeeeee;">查看上个月</a> <a href="StatMonth.php?y={$qYear}&m={$qMonth+1}" style="color:#eeeeee;">查看下个月</a></td></tr>                     
            <tr ><th colspan="10" style="color:#4D4D4D;">{$qYear}年{$qMonth}月销售统计（成交笔数:{$monthStatistics.finished},销售额:{$monthStatistics.payment}, 利润:{$monthStatistics.profits},流失笔数:{$monthStatistics.closed},流失金额:{$monthStatistics.closed_payment}，流失利润:{$monthStatistics.closed_profits}）</th></tr> 
            <tr style="background-color: #F5F5F5; color:#fff;   border-bottom: #666 solid 1px;">
                <th style="width:80px;text-align: center;">日期</th>
                <th style="width:60px;text-align: right;">总下单数</th>
                <th style="width:60px;text-align: right;">交易成功</th>
                <th style="width:60px;text-align: right;">销售额</th>
                <th style="width:60px;text-align: right;">邮费</th>
                <th style="width:60px;text-align: right;">利润</th>
                <th style="width:60px;text-align: right;">交易失败</th>
                <th style="width:60px;text-align: right;">流失金额</th>
                <th style="width:60px;text-align: right;">流失利润</th>
                <th style="width:50px;text-align: center;">操作</th>
            </tr>
            {foreach from=$dayStatistics key=ykey item=ystat}    
                {foreach from=$ystat item=mstat key=mkey}  
                    {foreach from=$mstat item=dstat key=dkey}  
                        <tr style=" background-color: {cycle values="#FFFFFF,#F5F5F5"}">
                            <td style="text-align: center;"><a href="TradeDay.php?y={$ykey}&m={$mkey}&d={$dkey}">{$ykey}年{$mkey}月{$dkey}日</a></td>
                            <td style="text-align: right;">{$dstat.finished+$dstat.closed}</td>
                            <td style="text-align: right;">{$dstat.finished}</td>
                            <td style="text-align: right;">{$dstat.payment}</td>
                            <td style="text-align: right;">{$dstat.post_fee}</td>
                            <td style="text-align: right;">{$dstat.profits}</td>
                            <td style="text-align: right;">{$dstat.closed}</td>
                            <td style="text-align: right;">{$dstat.closed_payment}</td>
                            <td style="text-align: right;">{$dstat.closed_profits}</td>
                            <td style="text-align: center;"><a href="TradeDay.php?y={$ykey}&m={$mkey}&d={$dkey}">查看</a></td>
                        </tr>     
                    {/foreach}   
                {/foreach}       
            {/foreach}
            <tr style=" background-color: {cycle values="#FFFFFF,#F5F5F5"};color:#4D4D4D;">
                <th><span title="(客服,邮费,赠品,退换)">额外成本</span>:{$monthStatistics.costs}</th>
                <td style="text-align: right;">{$monthStatistics.finished+$monthStatistics.closed}</th>
                <th style="text-align: right;">{$monthStatistics.finished}</th>
                <th style="text-align: right;">{$monthStatistics.payment}</th>
                <th style="text-align: right;">{$monthStatistics.post_fee}</th>
                <th style="text-align: right;">{$monthStatistics.profits}</th>
                <th style="text-align: right;">{$monthStatistics.closed}</th>
                <th style="text-align: right;">{$monthStatistics.closed_payment}</th>
                <th style="text-align: right;">{$monthStatistics.closed_profits}</th>
            </tr>   
            <tr style=" background-color: {cycle values="#FFFFFF,#f5f5f5"}">
                <td colspan="10"><h3 style="text-align: left;color:#4D4D4D;">友情提示：</h3>
            <ul>
                <li style="text-align: left;">当前还没有将戒指和非戒指这种需要赠送戒指圈的订单成本分离，而是全部增记5元成本。</li>
                <li style="text-align: left;">直通车广告费也没有考虑在内。</li>
            </ul>
            </td>
            </tr> 
        </table>
</div>