
<?php
include('conn.php');
?>
<!DOCTYPE html>
<html>
<body>

<?php
session_start();
echo $sql = "DELETE FROM `user_session` WHERE `session_hash`='".$_SESSION['hash']."' ";
if(mysqli_query($conn, $sql)){
    echo "USUNIETO!";
}
session_unset();
session_destroy();
setcookie("hash","0",time()-3600);
header('location:index.php');
?>

</body>
</html>