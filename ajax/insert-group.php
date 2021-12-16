<?php
include('default.php');
$shop= "SELECT * FROM `connected_shop` WHERE `shop_id`='".$_POST['shop-id']."' AND `user_id`='".$_SESSION['user_id']."' ";
$result_shop = mysqli_query($conn, $shop);
$row_shop = mysqli_fetch_array($result_shop,MYSQLI_ASSOC);
$wiel = count($_POST['child-id']);
$err = 0;
$sum = 0;
$sql_check = "SELECT id_glowny FROM `zestaw` WHERE `id_glowny`='".$_POST['main-id']." AND `user_id`='".$_SESSION['user_id']."' ";
$query_check = mysqli_query($conn,$sql_check);
$row_check = mysqli_fetch_array($query_check,MYSQLI_ASSOC);
if(!empty($row_check)){

$mess[] = Array(
"type" => "danger",
"strong" => "Na wszystkie borówki",
"mess" => "Zestaw z takim ID istnieje już w bazie!"
);

}else{

/////





$sql3 = "SELECT COUNT(`id_glowny`) as sumka FROM `zestaw` WHERE `user_id`='".$_SESSION['user_id']."' AND `id_produktu`=`id_glowny` ";
$query3 = mysqli_query($conn,$sql3);
$row3 = mysqli_fetch_array($query3,MYSQLI_ASSOC);

$limit = 0;
$ile = checkLimit($row3['sumka'],$wiel);

if($ile!=0){
$mess[] = Array(
"type" => "danger",
"strong"=>"Uwaga!",
"mess" => "Limit dodanych zestawów  został osiągnięty, nie dodałem zestawu"
);

}else{

$sql3 = "SELECT COUNT(`id_glowny`) as sumka FROM `zestaw` WHERE `user_id`='".$_SESSION['user_id']."' AND `id_produktu`<>`id_glowny` ";
$query3 = mysqli_query($conn,$sql3);
$row3 = mysqli_fetch_array($query3,MYSQLI_ASSOC);

$limit = 0;
$ile = checkLimit($row3['sumka'],$wiel);
if($ile!=0){
$mess[] = Array(
"type" => "danger",
"strong"=>"Uwaga!",
"mess" => "Limit dodanych produktów został osiągnięty, dodałem do limitu"
);
$wiel = $ile;
}
$errnum = [];
for($i=0;$i<$wiel;$i++){ 
    $Params = [
        "function" => "getProductData",
        "APIkey" => $row_shop['shop_api'],
        "productID" => $_POST['child-id'][$i]
        ];
        $ans = apiSkyShop($Params,$row_shop['shop_link']); 
    if(isset($ans->prod_amount)){
       $sql2 .= "
        INSERT INTO `zestaw`(`id_glowny`, `id_produktu`, `ilosc`, `ss_ilosc`,`ss_sprzedaz`,`ss_zakup`,`ss_nazwa`,`user_id`,`shop_id`) VALUES ('".$_POST['main-id']."','".$_POST['child-id'][$i]."','".$_POST['child-ilosc'][$i]."','".$ans->prod_amount."','".$ans->prod_price."','".$ans->prod_buy_price."','".$ans->prod_name."','".$_SESSION['user_id']."','".$_POST['shop-id']."');";
        $sum += ($ans->prod_buy_price*$_POST['child-ilosc'][$i]);
        
    }else{
        $err = 1;
        $errnum[] = $_POST['child-id'][$i];
    }
    }


    if(!isset($_POST['no-new-product'])){
    $Params = [
        "function" => "getProductData",
        "APIkey" => $row_shop['shop_api'],
        "productID" => $_POST['main-id']
        ];
        $ans = apiSkyShop($Params,$row_shop['shop_link']);      
    if(isset($ans->prod_amount)){
       $sql2 .= "
        INSERT INTO `zestaw`(`id_glowny`, `id_produktu`, `ilosc`, `ss_ilosc`,`ss_sprzedaz`,`ss_zakup`,`ss_nazwa`,`user_id`,`shop_id`) VALUES ('".$_POST['main-id']."','".$_POST['main-id']."','1','".$ans->prod_amount."','".$ans->prod_price."','".$ans->prod_buy_price."','".$ans->prod_name."','".$_SESSION['user_id']."','".$_POST['shop-id']."');";
    }else{
        $errnum[] = $_POST['child-id'][$i];
        $err = 1;
    }
    }
    if($err==0){
        //robimy normalnie, chłopaki dobrze robią
        if (mysqli_multi_query($conn, $sql2)) {
            //dodanie ceny zakupu

if(!isset($_POST['no-b-price'])){

$books[] =array(
    'prod_id' => $_POST['main-id'],
    'prod_buy_price' => $sum
);

$xml_data = new SimpleXMLElement('<?xml version="1.0"?><products></products>');

// function call to convert array to xml
array_to_xml($books,$xml_data);

//saving generated xml file;
$result = $xml_data->asXML();
$Params = [
"function" => "addUpdateProducts",
"APIkey" => $row_shop['shop_api'],
"importType" => "update",
"xml"=>$result
];
$ans = apiSkyShop($Params,$row_shop['shop_link']);    

}



$mess[] = Array(
"type" => "primary",
"strong"=>"święta makrelo!",
"mess" => "Zestaw został dodany, możesz go zobaczyć na liście ze wszystkimi zestawami"
);
if($limit == 1){

    $mess[] = Array(
"type" => "danger",
"strong"=>"Uwaga!",
"mess" => "Była podjęta próba dodania większej ilości produktów niż pozwala na to twój aktualny limit, dodałem tylko produkty do wyrównania limitu"
);

}



} else {
echo "Error: " . $sql . "<br>" . mysqli_error($conn);
$mess[] = Array(
"type" => "danger",
"strong" => "Coś nie bangla",
"mess" => "Błąd SQL"
);
}
}else{
    
$mess[] = Array(
"type" => "danger",
"strong" => "Na wszystkie borówki",
"mess" => "Produkt który chcesz dodać nie istnieje w skyshopie :( sprawdź dokładnie ID produktów (".implode($errnum,",").")"
);
}
}
}

echo json_encode($mess);
