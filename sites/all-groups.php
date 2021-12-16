<?php
if(!defined('directly')){
    echo "not allowed :(";
  exit();  
}?>
<div class='row px-3'>
    <div class='col-6'>
        <div class="row form-check py-2">
            <input type='checkbox' class='form-check-input' id='select-all'>
            <label class="form-check-label " for='select-all'>Zaznacz wszystko</label>
        </div>
        <div class='row form-group py-2'>
            <button type='button' class='btn btn-success btn-sm tip clipboard' data-toggle="tooltip" data-placement="top"
                title="Skopiuj ID wszystkich zaznaczonych produktów żeby później łatwo wkleić w wyszukiwarce SS">KOPIUJ
                ZAZNACZONE</button>
        </div>
    </div>
    <div class='col-6'>
        ?
    </div>
</div>
<table class='table table-striped table-bordered dataTable'>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nazwa zestawu</th>
            <th>ilość SS</th>
            <th>Max zestawów</th>
            <th>Sprzedaż SS</th>
            <th>Preferowana cena</th>c
            <th>Dodano</th>
            <th>Źródło</th>
            <th>btns</th>
        </tr>
    </thead>
    <tbody>
        <?php
$sql = "SELECT * FROM `zestaw` INNER JOIN `connected_shop` ON `connected_shop`.`shop_id`=`zestaw`.`shop_id` WHERE `zestaw`.id_glowny=`zestaw`.id_produktu AND `zestaw`.`user_id`='".$user['user_id']."'";
$result = mysqli_query($conn, $sql);

while($row = mysqli_fetch_array($result)){
//  $Params = [
//     "function" => "getProductData",
//     "APIkey" => "6bb1a475",
//     "productID"=>$row['id_produktu']
// ];
//  $ans = apiSkyShop($Params);
 if($row['id_glowny']==$row['id_produktu']){
     ?>
        <tr>
            <?php
 }elseif($row['ilosc']>$row['ss_ilosc']){
?>
        <tr class='table-danger' title="Produktu nie starczy na zamówienie!">
            <?php

 }else{
?>
        <tr>
            <?php
}

 ?>
            <td>
                <div class="form-check">
                    <input type='checkbox' class='form-check-input select-this-one' value='<?=$row['id_glowny']?>'>
                    <label class="form-check-label" for='select-all'><?=$row['id_glowny']?></label>
                </div>
            </td>
            <td class='ss-prod_name'><?=$row['ss_nazwa']?></td>
            <?php
$sql_ilo = "SELECT * FROM `zestaw` WHERE `id_glowny`<>`id_produktu` AND `id_glowny`=".$row['id_glowny']." ORDER BY ss_ilosc LIMIT 1";
$res_ilo = mysqli_query($conn, $sql_ilo);
while($row_ilo = mysqli_fetch_array($res_ilo)){
$ile_mozna = $row_ilo['ss_ilosc']/$row_ilo['ilosc'];
if($ile_mozna<1){
//czerwony alert za mało produktu
?>
            <td class='ss-prod_amount table-danger' title="Za mało produktów potrzebnych do utworzenia zestawu"><i class="fas fa-exclamation-triangle text-danger text-danger"></i>
                <?php
}elseif($ile_mozna==1){
?>
            <td class='ss-prod_amount table-dangertip' title="Ostatni zestaw na stanie"><i class="fas fa-exclamation-triangle text-danger"></i>
                <?php
}elseif($ile_mozna<5){
//żółty alert, 5 można zrobić
?>
            <td class='ss-prod_amount table-dangertip' title="Z dostarczonych danych wynika że będzie można stworzyć jeszcze 5 zestawów"><i class="fas fa-exclamation-triangle text-warning"></i>
                <?php
}elseif((int)$ile_mozna!=$row['ss_ilosc']){
?>
            <td class='ss-prod_amount' title="Stan zestawów nie zgadza się z tym w SS"><i class="fas fa-exclamation-triangle text-info"></i>
                <?php
}else{
?>
            <td class='ss-prod_amount'>
                <?php
}
}
?>
                <?=$row['ss_ilosc']?></td>
                <td><?= (int)$ile_mozna?></td>
            <?php
$sql_sum = "SELECT * FROM `zestaw` WHERE `id_glowny`<>`id_produktu` AND `id_glowny`=".$row['id_glowny']." ";
$res_sum = mysqli_query($conn, $sql_sum);
$sum = 0;
while($row_sum = mysqli_fetch_array($res_sum)){
$sum+=$row_sum['ss_zakup']*$row_sum['ilosc'];
}

if($row['ss_sprzedaz']>$sum*1.42){
?>
            <td class='ss-prod_price'>
                <?php
}else{
?>
            <td class='ss-prod_price' ><i class="fas fa-exclamation-triangle text-warning" title='Cena zakupu produktów jest większa od ceny sprzedaży!!!'></i>
                <?php
}
            ?>

                <?=$row['ss_sprzedaz']?></td>
                <td><?=number_format($sum*1.42,2,',', '')?></td>
            <?php
$sum+=$row['ss_zakup']*$row['ilosc'];
            ?>


            <td><?=$row['adddate']?></td>
            <td><?=$row['shop_name']?></td>
            <td>
                <button data-id='<?=$row['id_prim']?>' class='btn btn-danger btn-sm remove-this-product m-1'
                    data-toggle="tooltip" data-placement="top"
                    title="Usunięcie produktu z programu , nie równa się usunięciem ze SS - poprostu stany nie będą aktualizowane">USUŃ</button>
                <button data-shop='<?=$row['shop_id']?>' data-id='<?=$row['id_glowny']?>'
                    class='btn btn-primary btn-sm m-1 this-group-details'>ROZWIŃ</button>
            </td>
        </tr>

        <?php
}
        ?>
    </tbody>
</table>