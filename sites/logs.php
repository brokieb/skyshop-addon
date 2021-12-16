<?php
if(!defined('directly')){
    echo "not allowed :(";
  exit();  
}?>
<h4>Logi z działania automatu</h4>
<?php
include('servers/logs/'.$_SESSION['user_id'].'|disput.txt');

?>
