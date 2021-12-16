<?php
$sql = "SELECT * FROM `mail_template` WHERE `user_id` =".$_SESSION['user_id']."  ";
$query = mysqli_query($conn,$sql);
?>
<form class='row w-50'>
  <textarea class="form-control" placeholder="Leave a comment here" name='comment' for='send-answer' id="floatingTextarea2" style="height: 250px"></textarea>
<select class="form-select change-subject my-2" aria-label="Default select example">
    <option selected disabled>Wybierz template do edycji</option>
<?php
while($row = mysqli_fetch_array($query,MYSQLI_ASSOC)){
  ?>
  <option data-text="<?=$row['template']?>" value="<?=$row['id_template']?>"><?=$row['Subject']?></option>
  <?php
  }
    ?>
  </select>
<button type='button' class='btn btn-success my-2 save-template'>AKTUALIZUJ</button>
</form>


<?php
$api = "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE2Mjg1MzkyMTIsInVzZXJfbmFtZSI6IjQ3NzU0MDc1IiwianRpIjoiYTdkZjM4NWEtZmIyYi00MjJlLWE2Y2MtODE1OTRhN2UyOWZjIiwiY2xpZW50X2lkIjoiZDYwYmJlMmRmZDRhNDdjYzg0ZjhhY2VlZmIwZjY3NmYiLCJzY29wZSI6WyJhbGxlZ3JvOmFwaTpvcmRlcnM6cmVhZCIsImFsbGVncm86YXBpOnByb2ZpbGU6d3JpdGUiLCJhbGxlZ3JvOmFwaTpzYWxlOm9mZmVyczp3cml0ZSIsImFsbGVncm86YXBpOmJpbGxpbmc6cmVhZCIsImFsbGVncm86YXBpOmNhbXBhaWducyIsImFsbGVncm86YXBpOmRpc3B1dGVzIiwiYWxsZWdybzphcGk6c2FsZTpvZmZlcnM6cmVhZCIsImFsbGVncm86YXBpOmJpZHMiLCJhbGxlZ3JvOmFwaTpvcmRlcnM6d3JpdGUiLCJhbGxlZ3JvOmFwaTphZHMiLCJhbGxlZ3JvOmFwaTpwYXltZW50czp3cml0ZSIsImFsbGVncm86YXBpOnNhbGU6c2V0dGluZ3M6d3JpdGUiLCJhbGxlZ3JvOmFwaTpwcm9maWxlOnJlYWQiLCJhbGxlZ3JvOmFwaTpyYXRpbmdzIiwiYWxsZWdybzphcGk6c2FsZTpzZXR0aW5nczpyZWFkIiwiYWxsZWdybzphcGk6cGF5bWVudHM6cmVhZCJdLCJhbGxlZ3JvX2FwaSI6dHJ1ZX0.S3BEcS0wY9OIxWF9K12rKTy-VH9YVIdSDl0dbCN_CQm_TxLxhPQqmlD_xxc1CYiS6MqT0qxSK6VnDLCUKjfoUr4tdwt23OgPg-2_pyBRMSUonAN5GHIteX2cZtMkiwv9p0-ChYB-aMl7u89n_lFBEMin3aRnka_jk8dWDP76typpcEc20q_Ljy6t8OqeP9RY8lPWERsLZOd5MHaAPPdLzMzelFrS5K_e_Kqj6pmQz8QwzNwSP7tHYqw-TbH7u3_kqvjP3Ij7oJG9MGfWV_B_FgrVyC8OvcTBh6McmgdODzPJKo8ZJF_bHgR0gYsZcV_9orhvrfaeIvVNJwl5d679cQ";

        $det = restGet("/sale/offers?sort=stock.sold&limit=1000", $api);
foreach($det as $x){
// print_r($x);
if(is_array($x)){

foreach($x as $w){
  // print_r($w->id);
  $tis = restget("/pricing/offer-quotes?offer.id=".$w->id,$api);
  echo "<pre>";
  foreach($tis->quotes as $a){
    print_r($a->name);
  }
  // print_r($tis);
  echo "</pre>";
}
}
}
// print_r($det[0]->id);
?>