<?php

$user = UserData::getById($_SESSION["user_id"]);

if($user->kind==1){
    $order = BuyOrderData::getById($_GET["id"]);
    if(is_null($order->status) || $order->status==BuyOrderData::STATUS_PENDING){
        BuyOrderData::delById($order->id);
        Core::alert("Orden eliminada exitosamente!");
        print "<script>window.location='index.php?view=buyorders';</script>";

    }else{
        Core::alert("Error. No puedes eliminar una orden firmada!");
        print "<script>window.location='index.php?view=buyorders';</script>";

    }
}
?>
