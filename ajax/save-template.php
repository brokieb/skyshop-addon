wwa
<?php
include('default.php');
$sql = "UPDATE `mail_template` SET `template`='".$_POST['text']."' WHERE `id_template`=".$_POST['id']." ";
if(mysqli_query($conn,$sql)){
    echo "OK!";
}
?>