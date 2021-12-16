<?php
if(!defined('directly')){
    echo "not allowed :(";
  exit();  
}?>
<div class='row'>
    <div class='col-6'>
        <h2>Zarejestrowane konta</h2>
        <table class='table table-strippeds'>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Prawa</th>
                    <th>Pakiet</th>
                </tr>
            </thead>
            <tbody>


                <?php
$sql = "SELECT * FROM `user`;";
$query = mysqli_query($conn,$sql);
while($row = mysqli_fetch_array($query,MYSQLI_ASSOC)){
?>
                <tr>
                    <td><?=$row['user_id']?></td>
                    <td><?=$row['user_email']?></td>
                    <td><?=$row['user_privilege']?></td>
                    <td>
                        <form method='POST' class='row d-flex justify-content-between'>
                            <select name='bundle_id' class='form-control form-control-sm col-8'>

                                <?php
$sql2 = "SELECT * FROM `bundle` ";
$query2 = mysqli_query($conn,$sql2);
while($row2 = mysqli_fetch_array($query2,MYSQLI_ASSOC)){
    if($row2['bundle_id']==$row['bundle_id']){
        ?>
                                <option selected='selected' value='<?=$row2['bundle_id']?>'><?=$row2['bundle_name']?> | <?=$row2['bundle_price']?>
                                </option>
                                <?php
    }else{
         ?>
                                <option value='<?=$row2['bundle_id']?>'><?=$row2['bundle_name']?> | <?=$row2['bundle_price']?></option>
                                <?php
    }
?>

                                <?php
}
?>
                            </select>
                    <button class='btn  btn-sm col-2 btn-primary '>OK</button>
                    <input type='hidden' name='mode' value='3'>
                    <input type='hidden' name='user_id' value='<?=$row['user_id']?>'>
                        </form>
                    </td>
                </tr>

                <?php
}
?>
            </tbody>
        </table>
    </div>
    <div class='col-6'>
        <h2>Historia płatności</h2>
        <table class='table table-strippeds'>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Ilość</th>
                    <th>Status</th>
                    <th>ID pakietu</th>
                    <th>ID usera</th>
                </tr>
            </thead>
            <tbody>
                <?php
$sql  = "SELECT * FROM `buy_history` ";
$query = mysqli_query($conn,$sql);
while($row = mysqli_fetch_array($query,MYSQLI_ASSOC)){
?>
                <tr>
                    <td><?=$row['buy_id']?></td>
                    <td><?=$row['buy_datetime']?></td>
                    <td><?=$row['buy_quantity']?></td>
                    <td><?=$row['buy_status']?></td>
                    <td><?=$row['bundle_id']?></td>
                    <td><?=$row['user_id']?></td>
                </tr>
                <?php
}

?>
            </tbody>
            <tbody>
    </div>
</div>