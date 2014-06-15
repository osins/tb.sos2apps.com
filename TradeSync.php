<?php
include("commons/Global.php");
include("commons/topclient/TradeClient.php");

try{
    $trade = new TradeClinet($currentUser);
    $trade->sync();
    
    $page->loadTpl('TradeSync.tpl');
    
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

?>
