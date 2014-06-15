<?php
include("commons/Global.php");
include("commons/topclient/DeliveryTemplateClient.php");

try{
    $client = new DeliveryTemplateClient($currentUser);
    $client->getTemplate("1145327160");
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
?>
