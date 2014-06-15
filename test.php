<?php
require_once "commons/Global.php";
require_once "commons/domain/AreaDM.php";
require_once "commons/domain/DeliveryFeeDM.php";

$client = new ProductClient($currentUser);
$client->getList();
?>
