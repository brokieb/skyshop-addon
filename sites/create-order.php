<?php
$api = "SELECT * FROM `connected_shop` WHERE `user_id`=".$_SESSION['user_id']." ";
$query_api = mysqli_query($conn,$api);
$row_api = mysqli_fetch_array($query_api,MYSQLI_ASSOC);
$Params = [
    "function" => "getProducts",
    "APIkey" => $row_api['shop_api'],
    'start' => '0',
    'limit' => '1000',
    "search" => 'prod_sales=EXPORT[OR]prod_sales=G4Garage'
    ];
    $ans = apiSkyShop($Params, $row_api['shop_link']);
    // print_r($ans);
    foreach($ans as $key => $value){
        echo $value->prod_sales.'=='.$value->prod_name."---"."</br>";
    }

?>