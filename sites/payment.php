<?php
if(!defined('directly')){
    echo "not allowed :(";
  exit();  
}?>
<?php
$sql = "SELECT * FROM `user` LEFT JOIN `bundle` ON `user`.`bundle_id`=`bundle`.`bundle_id` WHERE `user`.`user_id`='".$user['user_id']."' ";
$query = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
?>
<h4>Twój aktualny pakiet to <?=$row['bundle_name']?></h4>
<h6>Z pakietu wykorzystano:</h6>
<ul>
<?php
$sql = "SELECT COUNT(`id_glowny`) as sumka FROM `zestaw` WHERE `user_id`='".$user['user_id']."' AND `id_produktu`<>`id_glowny` ";
$query = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
?>
<li>Ilość dodanych produktów ( <?=$row['sumka']?> / 5 )</li>
<?php
$sql = "SELECT COUNT(`id_glowny`) as sumka FROM `zestaw` WHERE `user_id`='".$user['user_id']."' AND `id_produktu`=`id_glowny` ";
$query = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
?>
<li>Ilość utworzonych zestawów ( <?=$row['sumka']?> / 1 )</li>
<?php
$sql = "SELECT COUNT(`shop_id`) as sumka FROM `connected_shop` WHERE `user_id`='".$user['user_id']."'";
$query = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
?>
<li>Ilość podpiętych API sklepów ( <?=$row['sumka']?> / 1 )</li>
</ul>


<i> W przyszłości zostanie udostępniony automat ułatwiający zakup odpowiadającego Ci pakietu, aktualnie jeżeli jesteś zainteresowany/a jednym z poniższych pakietów zapraszam do kontaktu :)</i></br>
<i> Wystawiamy fakturę VAT :)</i>
<table class='table table-striped'>
<thead>
<tr>
<th>Nazwa pakietu</th>
<th>obsługiwanych produktów</th>
<th>obsługiwanych zestawów</th>
<th>urządzeń jednocześnie</th>
<th>Max sklepów</th>
<th>Cena</th>
</tr>
</thead>
<tbody>
<?php
$sql = "SELECT * FROM `bundle`";
$query = mysqli_query($conn,$sql);
while($row = mysqli_fetch_array($query,MYSQLI_ASSOC)){
    ?>
<tr>
<td><?=$row['bundle_name']?></td>
<td><?=$row['bundle_max_products']?></td>
<td><?=$row['bundle_max_groups']?></td>
<td><?=$row['bundle_max_users']?></td>
<td><?=$row['bundle_max_shops']?></td>
<td><?=$row['bundle_price']?></td>
</tr>

    <?php
}


?>
</tbody>
</table>