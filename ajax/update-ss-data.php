<?php
include('default.php');
$shop= "SELECT * FROM `connected_shop` WHERE `shop_id`='".$_POST['shop_id']."' " ;
$result_shop = mysqli_query($conn, $shop);
$row_shop = mysqli_fetch_array($result_shop,MYSQLI_ASSOC);

$sql = "SELECT DISTINCT `id_produktu` FROM `zestaw` WHERE `id_glowny`=".$_POST['id']."";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
    $ids[] = $row['id_produktu'];
}
$ids[] = $_POST['id'];
$Params = [
    "function" => "getProducts",
    "APIkey" => $row_shop['shop_api'],
    "start"=>"0",
    "limit"=>"1000",
    "search_ids" => implode(",",$ids)
    ];
    $ans = apiSkyShop($Params,$row_shop['shop_link']);
    $ids = [];
    foreach($ans as $z){
        if(isset($z->prod_amount)){

            $sql2 = "UPDATE `zestaw` SET `ss_ilosc`='".$z->prod_amount."',`ss_sprzedaz`='".$z->prod_price."',`ss_zakup`='".$z->prod_buy_price."',`ss_nazwa`='".$z->prod_name."' WHERE `id_produktu`='".$z->prod_id."' ";
            if(mysqli_query($conn,$sql2)){
                $ids[$z->prod_id] = array(
                    'prod_name' => $z->prod_name,
                    'prod_amount' => $z->prod_amount,
                    'prod_price' => $z->prod_price,
                    'prod_buy_price' => $z->prod_buy_price
                );
            }

        }
       
        }
       echo json_encode($ids);
?>