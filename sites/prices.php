
<form class='w-25' method='POST' action='index.php?site=prices'>
<!-- <input type='hidden' name='site' value='<?=$_GET['site']?>'> -->
  <div class="mb-3 ">
    <label for="from-id" class="form-label">ID od</label>
    <input type="number" name='from-id'class="form-control" id="from-id" aria-describedby="emailHelp">
  </div>
  <div class="mb-3">
    <label for="to-id" class="form-label">ID do</label>
    <input type="number" name='to-id'class="form-control" id="to-id" aria-describedby="emailHelp">
  </div>
  <button type="submit" class="btn btn-primary">GENERUJ :)</button>
</form>
<?php
$list = array();
if(isset($_POST['from-id'])){


for($i=$_POST['from-id'];$i<=$_POST['to-id'];$i++){
    $list[] = $i;
}
$api = "SELECT * FROM `connected_shop` WHERE `user_id`=".$_SESSION['user_id']." ";
$query_api = mysqli_query($conn,$api);
$row_api = mysqli_fetch_array($query_api,MYSQLI_ASSOC);
$row_api['shop_api'];
echo $sql = "SELECT * FROM `zestaw` INNER JOIN `connected_shop` ON `connected_shop`.`shop_id`=`zestaw`.`shop_id` WHERE `zestaw`.`user_id`=".$_SESSION['user_id']." AND `id_glowny`=`id_produktu` AND `id_glowny` IN ('".implode("','",$list)."');";
$query = mysqli_query($conn,$sql);
$delete_val = array();
$j = 0;
$receptura = null;
while($row = mysqli_fetch_array($query,MYSQLI_ASSOC)){
    echo $row;
  
    $find = array_search($row['id_produktu'], $list);
    unset($list[$find]);
    $Params = [
        "function" => "getProducts",
        "APIkey" => $row_api['shop_api'],
        "start"=>"0",
        "limit"=>"999",
        "search_ids" => $row['id_produktu'],
        ];
        print_r($Params);
        $ans = apiSkyShop($Params, $row_api['shop_link']);
    $data['ROOT']['TOWARY'][] = array(
        'TOWAR' => array(
        'KOD' => $row['id_glowny'],
        'KOD_H' => $row['id_glowny'],
        'TYP' => '1',
        'PRODUKT' => '1',
        'NAZWA' => $row['ss_nazwa'],
        'GRUPA' => $ans->{0}->prd_name,
        'JM' => $ans->{0}->prod_unit,
        'DOSTAWCA' => array(
            'KOD_U_DOSTAWCY' => $ans->{0}->prod_symbol
        ),
        'STAWKA_VAT' => array(
            'STAWKA' => $ans->{0}->prod_tax_value
        ),
        'CENY' => array(
            'CENA' => array(
                'WARTOSC' => $ans->{0}->prod_price,
                'SYMBOL_WALUTY' => 'PLN'
            )
        )
            ));
           echo $sql_rec = "SELECT * FROM `zestaw` WHERE `user_id`=".$_SESSION['user_id']." AND `id_glowny`<>`id_produktu` AND `id_glowny`=".$row['id_glowny']." ";
            $query_rec = mysqli_query($conn,$sql_rec);
            $i = 1;
            $skladnik = null;
            while($row_rec = mysqli_fetch_array($query_rec,MYSQLI_ASSOC)){
                $skladnik[] = array('SKLADNIK' => array(
                    'LP' => $i,
                    'ILOSC' => $row_rec['ilosc'],
                    'JM' => 'szt.',
                    'ILOSC_JM' => $row_rec['ilosc'],
                    'MAGAZYN_SYMBOL' => 'MAGAZYN',
                    'KOD_SKLADNIKA' => $row_rec['id_produktu']
                ));
    $i++;
            }
            $receptura[] = array('RECEPTURA' => array(
                'KOD' => $row['id_glowny'],
                'ILOSC' => '1.0000',
                'JM' => $ans->{0}->prod_unit,
                'KOD_PRODUKTU' => $row['id_glowny'],
                'SKLADNIKI' => $skladnik
            ));
            $data['ROOT']['RECEPTURY'][] = $receptura;
}
$Params = [
    "function" => "getProducts",
    "APIkey" => $row_api['shop_api'],
    "start"=>"0",
    "limit"=>"1000",
    "search_ids" => implode(",",$list),
    ];
    print_r($Params);
    $ans = apiSkyShop($Params, $row_api['shop_link']);
    print_r($ans);
    foreach($ans as $z){
        if(isset($z->prod_id)){

       
        $data['ROOT']['TOWARY'][] = array(
            'TOWAR' => array(
            'KOD' => $z->prod_id,
            'KOD_H' => $z->prod_id,
            'TYP' => '1',
            'PRODUKT' => '0',
            'NAZWA' => $z->prod_name,
            'GRUPA' => $z->prd_name,
            'JM' => $z->prod_unit,
            'DOSTAWCA' => array(
                'KOD_U_DOSTAWCY' => $z->prod_symbol
            ),
            'STAWKA_VAT' => array(
                'STAWKA' => $z->prod_tax_value
            ),
            'CENY' => array(
                'CENA' => array(
                    'WARTOSC' => $z->prod_price,
                    'SYMBOL_WALUTY' => 'PLN'
                )
            )
                ));

            }
    }


$data['ROOT']['PRODUCENCI'] = array();
$data['ROOT']['MARKI']= array();
$xmlRequest = ArrToXml::parse($data);
$link = uniqid();
$fp = fopen('cenniki/'.$link.'.xml', 'w');
fwrite($fp, $xmlRequest);
fclose($fp);
?>
<a href='cenniki/<?=$link?>.xml' target='_blank'>Wygenerowany ccennik</a>

<?php
}

// echo "<pre>";
// print_r($data);
// echo "</pre>";

// // echo $xmlRequest;

// creating object of SimpleXMLElement
// $xml_data = new SimpleXMLElement('<?xml version="1.0"

?>