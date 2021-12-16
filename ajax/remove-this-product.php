<?php
include('default.php');
$sql = "SELECT * FROM `zestaw` WHERE id_prim=".$_POST['id'].";";
$query = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
if($row['id_glowny']==$row['id_produktu']){
$sql = "DELETE FROM `zestaw` WHERE id_glowny=".$row['id_glowny']." AND `user_id`=".$row['user_id'].";";
}else{
    $sql = "DELETE FROM `zestaw` WHERE id_prim=".$_POST['id'].";";
}

if($q = mysqli_query($conn, $sql)){
    $mess[] = Array(
        "type" => "primary",
        "strong"=>"O kepasa!",
        "mess" => "Produkt poprawnie usunięty"
        );
}else{
    $mess[] = Array(
        "type" => "danger",
        "strong"=>"Coś się skasztaniło :(",
        "mess" => "Nie udało się usunąć produktu"
        );
}
echo json_encode($mess);
?>
