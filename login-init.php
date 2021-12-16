<?php
include('conn.php');
echo $sql = "SELECT * FROM `user` INNER JOIN `bundle` ON `bundle`.`bundle_id`=`user`.`bundle_id` WHERE `user_email` = '".$_POST['email']."' LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
if(password_verify($_POST['password'],$row['user_password'])){
    //hasło poprawne
$hash = password_hash($_SERVER['REMOTE_ADDR'].":".$y,PASSWORD_DEFAULT);
  if($_POST['remember-me']=='on'){
$saved = 1;
  }else{
      $saved = 0;
  }
 echo $sql3 = "INSERT INTO `user_session` (`user_id`,`session_hash`,`session_saved`,`session_expired`) VALUES (
       '".$row['user_id']."',
       '".$hash."',
       '".$saved."',
       NOW() + INTERVAL 3 DAY
   )";


    if(mysqli_query($conn, $sql3)){
        echo "UTWORZONO SESJE!";
    $_SESSION['user_id'] = $row['user_id'];
    $_SESSION['hash'] = $hash;
     $_SESSION['created'] = time();
        if($_POST['remember-me']=='on'){
            setcookie('hash', $hash,  time()+86400*3);
        }
    }


    $sql2 = "SELECT * FROM user_session where user_id='" . $row['user_id'] . "' ORDER BY `user_session`.`session_id` DESC";
     $result2 = mysqli_query($conn, $sql2);
     $i = 0;
     while($session = mysqli_fetch_array($result2,MYSQLI_ASSOC)){
        $i++;
        if($i>$row['bundle_max_shops']||Date("Y-m-d H:i:s")>$session['session_expired']){
            echo "<br>";
            echo $sql3 = "DELETE FROM `user_session` WHERE `session_id`=".$session['session_id'];
            if(mysqli_query($conn,$sql3)){
                echo "OK";
            }
        }
    }


    header('location:index.php');

   
        

}else{
    //hasło niepoprawne
   $_SESSION['alerts'][$uid = uniqid()] = Array(
    'add' => time(),
    'uid' => $uid,
    'type' => 'danger',
    'subject' => 'Błąd!',
    'value' => 'NIepoprawne dane logowania'
);
    header('location:index.php');
}
print_r($_POST);
print_r($_SESSION);
?>