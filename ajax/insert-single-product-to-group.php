<?php
include('default.php');
$err = 0;
$shop= "SELECT * FROM `connected_shop` WHERE `shop_id`='".$_POST['shop_id']."' AND `user_id`='".$_SESSION['user_id']."' ";
$result_shop = mysqli_query($conn, $shop);
$row_shop = mysqli_fetch_array($result_shop,MYSQLI_ASSOC);

$sql = "SELECT * FROM `connected_shop` WHERE `user_id`='".$_SESSION['user_id']."' AND `shop_id`='".$_POST['shop_id']."'  ";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($res,MYSQLI_ASSOC);

    $Params = [
        "function" => "getProductData",
        "APIkey" => $row['shop_api'],
        "productID" => $_POST['child_id']
        ];
        $ans = apiSkyShop($Params,$row['shop_link']);      
    if(isset($ans->prod_amount)){
       $sql2 = "
        INSERT INTO `zestaw`(`id_glowny`, `id_produktu`, `ilosc`, `ss_ilosc`,`ss_sprzedaz`,`ss_zakup`,`ss_nazwa`,`user_id`,`shop_id`) VALUES ('".$_POST['main_id']."','".$_POST['child_id']."','".$_POST['child_ilosc']."','".$ans->prod_amount."','".$ans->prod_price."','".$ans->prod_buy_price."','".$ans->prod_name."','".$_SESSION['user_id']."','".$_POST['shop_id']."');";
        
    }else{
        $err = 1;
    }
 



    if($err==0){
        //robimy normalnie, chłopaki dobrze robią
        if (mysqli_query($conn, $sql2)) {
            //dodanie ceny zakupu



$mess[] = Array(
"type" => "primary",
"strong"=>"Na ozyrysa!",
"mess" => "Produkt dodano, Zwiń i rozwiń szczegóły produktu żeby zobaczyć zmiany"
);


} else {
$mess[] = Array(
"type" => "danger",
"strong" => "Coś nie bangla",
"mess" => "Błąd SQL: " . $sql . "<br>" . mysqli_error($conn)
);
}
}else{
$mess[] = Array(
"type" => "danger",
"strong" => "Na wszystkie borówki",
"mess" => "Produkt który chcesz dodać nie istnieje w skyshopie :( sprawdź dokładnie ID produktu"
);
}
echo json_encode($mess);
?>