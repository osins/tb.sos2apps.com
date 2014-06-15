<?php
include("commons/AuthConfig.php");
include("commons/MemcacheControl.php");
include("commons/CurrentUser.php");

$top_code = $_GET['code'];
$url = "https://oauth.taobao.com/token";
$params= array(
    'grant_type'    => 'authorization_code',
    'client_id'     => TOP_APPKEY,
    'client_secret' => TOP_SECRET,
    'code'          => $top_code,
    'redirect_uri'  => 'http://tb.sos2apps.com/index.php'
    );

if($_GET['error_description']){
    echo $_GET['error_description'];
    die();
}

$token = json_decode(http($url, http_build_query($params), 'POST'));

if(!$token->access_token){
    $url = "Location: http://tb.sos2apps.com/AuthGetCode.php";
    header($url); 
    die();
}

$currentUser = new CurrentUser;
$currentUser->setExpires($token->expires_in);
$currentUser->setUserId($token->taobao_user_id);
$currentUser->setNick(urldecode($token->taobao_user_nick));
$currentUser->setSession($token->access_token);

echo "<br>";
echo "session key:";
echo $currentUser->getSession();
echo "<br>";

if(isset($_GET['redirect_uri'])){
    $url = "Location: http://tb.sos2apps.com".urldecode($_GET['redirect_uri']);
}else{
    $url = "Location: http://tb.sos2apps.com/index.php";
}

header($url); 

function http($url, $postfields='', $method='GET', $headers=array()){
    $ci=curl_init();
    curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ci, CURLOPT_TIMEOUT, 30);
    if($method=='POST'){
            curl_setopt($ci, CURLOPT_POST, TRUE);
            if($postfields!='')curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
    }
    $headers[]='User-Agent: Taobao.PHP(piscdong.com)';
    curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ci, CURLOPT_URL, $url);
    $response=curl_exec($ci);
    curl_close($ci);
    return $response;
}