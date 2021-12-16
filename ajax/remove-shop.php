<?php
include('default.php');

$sql = "DELETE FROM `connected_shop` WHERE `shop_id`='".$_POST['id']."' AND `user_id`='".$_SESSION['user_id']."' ";
if(mysqli_query($conn,$sql)){

$mess[] = Array(
"type" => "primary",
"strong"=>"Udało się!",
"mess" => "Sklep został pomyślnie odłączony!"
);

}


echo json_encode($mess);
?>