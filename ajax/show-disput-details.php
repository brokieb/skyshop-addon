<?php
include('default.php');
$sql = "SELECT `allegro_token` FROM `allegro_account` WHERE `user_id`=" . $_SESSION['user_id'] . " AND `allegro_id`=" . $_POST['sklep'] . " ";
$query = mysqli_query($conn, $sql);
$token = mysqli_fetch_array($query, MYSQLI_ASSOC);


$a = restGet('/sale/disputes/' . $_POST['id'] . "/messages", $token['allegro_token']);
?>
<div class='d-flex flex-column'>
  <?php
  $arr = array_reverse($a->messages, true);
  foreach ($arr as $x) {
    if ($x->author->role == "SELLER") {
      $float = 'float-end';
    } else {
      $float = 'float-start';
    }
  ?>
    <div class='w-100'>
      <div class="toast show my-2 <?= $float ?> d-block" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <strong class="me-auto"><?= $x->author->role ?> (<?= $x->author->login ?>)</strong>
          <small><?= date('Y-m-d H:i', strtotime($x->createdAt)) ?></small>
        </div>
        <div class="toast-body">
          <?= $x->text ?>
          <?php
        if (isset($x->attachment)) {
        ?>
            <form target="_blank" action="att.php" method='post'>
              <input type='hidden' name='token' value='<?= $token['allegro_token'] ?>'>
              <input type='hidden' name='attach' value='<?= $x->attachment->url ?>'>
              <input type='hidden' name='file' value='<?= $x->attachment->fileName ?>'>
              <button type='submit' class='btn btn-success btn-sm'>ZAŁĄCZNIK</button>
            </form>
        <?php
        }
        ?>
        </div>
        

      </div>
    </div>
  <?php
  }

  ?>
  <form method='POST' action='index.php?site=disput' id='send-answer'>
    <input type='hidden' name='mode' value='11'>
    <input type='hidden' name='id' value='<?= $_POST['id'] ?>'>
    <input type='hidden' name='sklep' value='<?= $_POST['sklep'] ?>'>
    <div class="form-check form-check-inline w-100 p-0">
      <select class="form-select change-subject" aria-label="Default select example">
        <option selected disabled>Open this select menu</option>
        <?php
        echo $sql = "SELECT * FROM `mail_template` WHERE `user_id`=" . $_SESSION['user_id'] . " and `source_name`='disput' ";
        $query = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        ?>
          <option data-text="<?= $row['template'] ?>"><?= $row['Subject'] ?></option>
        <?php
        }
        ?>
      </select>
    </div>
    <div class="form-floating mt-3">
      <textarea class="form-control" placeholder="Leave a comment here" name='comment' for='send-answer' id="floatingTextarea2" style="height: 250px"></textarea>
      <label for="floatingTextarea2">Twoja odpowiedź</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="close" value="1">
      <label class="form-check-label" for="inlineCheckbox1">Poproś o zamknięcie sporu</label>
    </div>

    <button type='submit' class='btn btn-primary m-2'>Odpowiedz</button>
  </form>

</div>

<script src='js/main.js'></script>