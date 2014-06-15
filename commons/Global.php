<?php

header("Content-type: text/html; charset=utf-8");

include("Console.php");
include("Singleton.php");
include("AuthConfig.php");
include("MemcacheControl.php");
include('commons/smarty/Smarty.class.php');
include("PdoConfig.php");
include("TopSdk.php");
include("CurrentUser.php");
include("PageControl.php");
include("domain/Domain.php");
include("topclient/TBClient.php");
include("topclient/ProductClient.php");


$currentUser = new CurrentUser;
$sessionkey = $currentUser->getSession();
if(!$sessionkey){
    $params = array(
        "redirect_uri"=>  urlencode($_SERVER['PHP_SELF']."?". http_build_query($_GET))
    );
    $url = "Location: http://tb.sos2apps.com/AuthGetCode.php?".  http_build_query($params);
    header($url); 
}

$page = new PageControl;

$page->assign("currentUserNick", $currentUser->getNick());
$page->assign("page_title","网店运营管理系统"); 
?>
