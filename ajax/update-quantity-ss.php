<?php
include('default.php');
$shop= "SELECT * FROM `connected_shop` WHERE `shop_id`='".$_POST['shop']."' AND `user_id`='".$_SESSION['user_id']."' ";
$result_shop = mysqli_query($conn, $shop);
$row_shop = mysqli_fetch_array($result_shop,MYSQLI_ASSOC);

$sql_ilo = "SELECT * FROM `zestaw` WHERE `id_glowny`<>`id_produktu` AND `id_glowny`=".$_POST['id']." AND `shop_id`='".$_POST['shop']."' ORDER BY ss_ilosc LIMIT 1";
$res_ilo = mysqli_query($conn, $sql_ilo);
while($row_ilo = mysqli_fetch_array($res_ilo)){
$ile_mozna = $row_ilo['ss_ilosc']/$row_ilo['ilosc'];
}

$books[] =array(
    'prod_id' => $_POST['id'],
    'prod_amount' => (int)$ile_mozna
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

if($ans->response_code=="200"){
        $sql = "UPDATE `zestaw` SET `ss_ilosc`='".(int)$ile_mozna."' WHERE `id_glowny`=".$_POST['id']." AND `shop_id`='".$_POST['shop']."' AND `id_glowny`=`id_produktu`";
        if(mysqli_query($conn,$sql)){
            $mess[] = Array(
                "type" => "success",
                "strong" => "OK",
                "mess" => "Poprawnie poprawiono ilość produktów, odśwież stronę"
                );
        }
}else{
    $mess[] = Array(
        "type" => "danger",
        "strong" => "Błąd",
        "mess" => "Nie zaktualizowałem danych produktu w SS, błąd: ".$ans->status
        );
}
echo json_encode($mess);

?>