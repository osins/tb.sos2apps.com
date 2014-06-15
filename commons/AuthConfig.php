<?php
ini_set('session.save_handler', 'memcache');
ini_set('session.cookie_domain','.sos2apps.com');
ini_set('session.save_path','tcp://localhost:11211?persistent=1&weight=1&timeout=1&retry_interval=15');  

session_start(); 

define('TOP_APPKEY', '21747979');
define('TOP_SECRET', '4e62fa2df2c8cea6d7a161af190f5274');
?>
