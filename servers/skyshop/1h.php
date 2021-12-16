<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include(dirname(__FILE__).'/../functions.php');
include(dirname(__FILE__).'/../conn.php');


$who = "SELECT * FROM `user` INNER JOIN `bundle` ON `user`.`bundle_id`=`bundle`.`bundle_id` INNER JOIN `connected_shop` ON `connected_shop`.`user_id`=`user`.`user_id` WHERE `bundle_script_time`='1h'";
$result_w = mysqli_query($conn, $who);
while($row_w = mysqli_fetch_array($result_w)){


$sql = "SELECT id_produktu,ss_ilosc FROM `zestaw` WHERE `id_glowny`<>`id_produktu` AND `user_id`='".$row_w['user_id']."' AND `shop_id`='".$row_w['shop_id']."'";//nowe stany reszty produktów
$result = mysqli_query($conn, $sql);
$ids = [];
$stan = [];
while($row = mysqli_fetch_array($result)){

    $ids[] = $row['id_produktu'];
    $stan[$row['id_produktu']] = $row['ss_ilosc'];
}
if(!empty($ids)){
$Params = [
    "function" => "getProducts",
    "APIkey" => $row_w['shop_api'],
    "start"=>"0",
    "limit"=>"1000",
    "search_ids" => implode(",",$ids),
    ];
    $ans = apiSkyShop($Params,$row_w['shop_link']);

    foreach($ans as $z){
        if(isset($z->prod_amount)){

     
$a = strstr($z->prod_amount, '.', true);
if($a>$stan[$z->prod_id]){
    echo $sql2 = "UPDATE `zestaw` SET `ss_ilosc`='".$a."' WHERE `id_produktu`=".$z->prod_id." ";
    if(mysqli_query($conn, $sql2)){
        inputLog("Zmieniłem stan magazynowy w bazie PRODUKT ".$z->prod_id." z nowym stanem (".$stan[$z->prod_id]." => ".$a.")",1,$row_w['user_id']);
        }else{
            inputLog("Nie mogę zaktualizować stanu w skyshopie PRODUKT ".$z->prod_id."  (".$stan[$z->prod_id]." => ".$a.")",3,$row_w['user_id']);
        }
}
    }
}





$sql = "SELECT id_glowny,ss_ilosc FROM zestaw WHERE `id_glowny`=`id_produktu` AND `user_id`='".$row_w['user_id']."'";//sprzedaż zestawu
$result = mysqli_query($conn, $sql);
$ids = [];
$ilo = [];
while($row = mysqli_fetch_array($result)){
$ids[] =$row['id_glowny'];
$ilo[$row['id_glowny']] = $row['ss_ilosc'];
}
$Params = [
"function" => "getProducts",
"APIkey" => "6bb1a475",
"start"=>"0",
"limit"=>"1000",
"search_ids" => implode(",",$ids),
];
$ans = apiSkyShop($Params,$row_w['shop_link']);
foreach($ans as $z){
    if(isset($z->prod_amount)){
$a = strstr($z->prod_amount, '.', true);
if($a<$ilo[$z->prod_id]){//produkt się sprzedał, wszędzie zmniejszamy stany
    $roz = $ilo[$z->prod_id]-$a;
    if($roz>=$ilo[$z->prod_id]){
        inputLog("Interweniuj! Nie pozwoliłem na zmianę stanu poniżej minimum (0), zamówienie nie zostanie już obsłużone :( zmień stany o ".$roz." dla produktów z zestawu ".$z->prod_id,0,'1');
    }else{
    echo $sql3 = "UPDATE `zestaw` SET `ss_ilosc`=`ss_ilosc`-(`ilosc`*".$roz.") WHERE id_glowny=".$z->prod_id;
    if(mysqli_query($conn, $sql3)){

    $sql4 = "SELECT id_produktu,ss_ilosc FROM `zestaw` WHERE `id_glowny`<>`id_produktu` AND
        id_glowny=".$z->prod_id;
        $result = mysqli_query($conn, $sql4);
        $books = [];
        $zmienione = [];
        while($row = mysqli_fetch_array($result)){

            $books[] =array(
                    'prod_id' => $row['id_produktu'],
                    'prod_amount' => $row['ss_ilosc']
            );
            $zmienione[]= $row['id_produktu'];
        }


        $xml_data = new SimpleXMLElement('<?xml version="1.0"?><products></products>');

// function call to convert array to xml
array_to_xml($books,$xml_data);

//saving generated xml file;
$result = $xml_data->asXML();

$Params = [
"function" => "addUpdateProducts",
"APIkey" => "6bb1a475",
"importType" => "update",
"xml"=>$result
];
$ans = apiSkyShop($Params,$row_w['shop_link']);
inputLog("Stan w SS (".$z->prod_id.") mniejszy zmieniłem w " . implode(",",$zmienione) ,0,$row_w['user_id']);
}
    }
}
}
}

}else{
    echo "PUSTO";
}
}
?>