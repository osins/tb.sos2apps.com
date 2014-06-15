<?php

include("commons/AuthConfig.php");
include("commons/MemcacheControl.php");
include("commons/CurrentUser.php");

$currentUser = new CurrentUser;
$currentUser->delSession();
?>
