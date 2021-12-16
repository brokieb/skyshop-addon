<div class='row border my-2 p-2 w-100'>
    <h2>Dyskusje</h2>
    <table class='table table-striped table-bordered w-75'>
    <thead>
    <tr>
    <td>SKLEP</td>
    <td>LOGIN</td>
    <td>TREŚĆ</td>
    <td>WIADOMOŚĆ Z</td>
    <td>Przyciski</td>
    </tr>
    </thead>
    <tbody>
 
<?php 
$sql = "SELECT * FROM `allegro_account` WHERE `user_id`=".$_SESSION['user_id']." ";
$query = mysqli_query($conn,$sql);
while($row = mysqli_fetch_array($query,MYSQLI_ASSOC)){
    $disputes = restGet('/sale/disputes',$row['allegro_token']);
foreach($disputes as $z){
    foreach($z as $y){
        $a = restGet('/sale/disputes/'.$y->id."/messages?limit=2",$row['allegro_token']);
        if($y->status=='ONGOING'&&$a->messages[0]->author->role=="BUYER"){
            ?>
<tr>
<td>( konsultant )<?=curlme($row['allegro_token'])->login?></td>
<td><?=$y->buyer->login?></td>
<td class='t'><?=$a->messages[0]->text?></td>
<td><?=date('m-d H:i', strtotime($a->messages[0]->createdAt))?></td>
<td><button class='btn btn-sm btn-primary ' data-bs-toggle="modal" data-title="Dyskusja z klientem <?=$y->buyer->login?>" data-bs-target="#myModal" data-site="show-disput-details" data-id="<?=$y->id?>" data-sklep="<?=$row['allegro_id']?>">ODP</button></td>
</tr>

            <?php
        }elseif($y->status=='ONGOING'&&$a->messages[0]->author->role=="SELLER"){
            ?>

<?php
            if(substr($a->messages[0]->text, 0, 7)=="Witaj! "){
                ?> 
<tr>
<td>(automat) <?=curlme($row['allegro_token'])->login?></td>
<td><?=$y->buyer->login?></td>
<td class='t'><?=$a->messages[1]->text?></td>
<td><?=date('m-d H:i', strtotime($a->messages[1]->createdAt))?></td>
<td><button class='btn btn-sm btn-primary ' data-bs-toggle="modal" data-title="Dyskusja z klientem <?=$y->buyer->login?>" data-bs-target="#myModal" data-site="show-disput-details" data-id="<?=$y->id?>" data-sklep="<?=$row['allegro_id']?>">ODP</button></td>
</tr>
                <?php
            }
?>


<?php
        }
    }
    
}
    }
?>
   </tbody>
    </table>
    </div>