<?php
include('default.php');
$shop= "SELECT * FROM `connected_shop` WHERE `shop_id`='".$_POST['shop_id']."' AND `user_id`='".$_SESSION['user_id']."' ";
$result_shop = mysqli_query($conn, $shop);
$row_shop = mysqli_fetch_array($result_shop,MYSQLI_ASSOC);


$sql = "SELECT * FROM zestaw WHERE `id_glowny`=".$_POST['id']." AND `id_glowny`<>`id_produktu` AND `user_id`='".$_SESSION['user_id']."' ";
$result = mysqli_query($conn, $sql);
?>
<div class='row'>
<h2 class='col-6'>Szczegóły zestawu</h2>
<h2 class='col-6'>
<button  data-id='<?=$_POST['id']?>' data-shop='<?=$_POST['shop_id']?>' class='btn btn-warning update-ss-data tip' data-toggle="tooltip" data-placement="top" title="Wymuś aktualizowanie wszystkich danych dla tego produktu ze SS ">AKTUALIZUJ CENY ZE SS</button>
<button  data-id='<?=$_POST['id']?>' data-shop='<?=$_POST['shop_id']?>' class='btn btn-primary send-new-quantity tip' data-toggle="tooltip" data-placement="top" title="Ilość w SS zmieni się na tą proponowaną przez program. ">ZAKTUALIZUJ ILOŚĆ ZESTAWÓW</button>

</h2>
</div>

<table>
<thead>
<tr>
<th>ID</th>
<th>W zest</th>
<th>Nazwa</th>
<th>Ilość SS</th>
<th>Cena sprzedaży SS</th>
<th>Cena zakupu SS</th>
<th>btns</th>
</tr>
</thead>
<tbody>
<?php
$sum_sprzedaz = 0;
$sum_zakup = 0;
while($row = mysqli_fetch_array($result)){
    $Params = [
        "function" => "getProductData",
            "APIkey" => $row_shop['shop_api'],
            "productID"=>$row['id_produktu']
        ];
        $ans = apiSkyShop($Params,$row_shop['shop_link']);
?>
<tr>
<td><?=$row['id_produktu']?></td>
<td><?=$row['ilosc']?></td>
<td><?=$ans->prod_name?></td>
<td><?=$row['ss_ilosc']?></td>
<td><?=$row['ss_sprzedaz']?></td>
<td><?=$row['ss_zakup']?></td>
<td><button class='btn btn-sm btn-danger remove-single-product' data-id='<?=$row['id_prim']?>'>USUŃ</button></td>
</tr>

<?php
$sum_sprzedaz += $row['ss_sprzedaz']*$row['ilosc'];
$sum_zakup += $row['ss_zakup']*$row['ilosc'];
}
?>
</tbody>
<tfoot>
<tr>
<td colspan='4' class='text-right'>Sumy</td>
<td><?=$sum_sprzedaz?></td>
<td><?=$sum_zakup?></td>
<td></td>
</tr>
<tr>
<td><input type='number' min="1" max="999999" class='form-control form-control-sm my-auto ss-child_id' style='width:70px' required='required' form='add-single-product'></td>
<td><input type='number' min="1" max="5" class='form-control form-control-sm my-auto ss-child_ilosc' required='required' form='add-single-product'></td>
<td colspan='5'><form method='POST' id='add-single-product' class='add-single-product'><button type='submit' class='btn btn-sm btn-success my-auto ' data-id='<?=$_POST['id']?>' data-shop='<?=$_POST['shop_id']?>'>DODAJ PRODUKT</button></form></td>
</tr>
</tfoot>
</table>