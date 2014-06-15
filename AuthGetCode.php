<?php
include("commons/AuthConfig.php");

$params = array(
        "redirect_uri"=>$_GET['redirect_uri']
    );
    
$params= array(
    'response_type'  => 'code',
    'client_id'     => TOP_APPKEY,
    'redirect_uri'  => 'http://tb.sos2apps.com/AuthCallback.php?'.http_build_query($params)
);

$url = "Location: https://oauth.taobao.com/authorize?".http_build_query($params);
header($url); 
die();  
?>
