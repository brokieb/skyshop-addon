<?php
if(!defined('directly')){
    echo "not allowed :(";
  exit();  
}?>
<div class='row d-grid gap-2'>
    <form method='POST' class='col-3' id='form'>
        <h2>Dodanie nowego sklepu</h2>
        <div class="mb-3">
            <label for='friendly_name' class="form-label my-2">Przyjazna nazwa </label>
            <input type='text' name='friendly_name' id='friendly_name' class="form-control  my-2" required="required" placeholder='ZABAWKI'>
        </div>
        <div class="mb-3">
            <label for='shop_link' class="form-label my-2">Link do sklepu </label>
            <input type='text' name='shop_link' id='shop_link' class="form-control  my-2 tip" data-toggle="tooltip" data-placement="top" title="Link do sklepów bez protokołu http/https - pamiętaj żeby uruchomić funkcjonalności api w samym SS" required="required" placeholder='twoj-sklep.pl/api'>
        </div>
        <div class="mb-3">
            <label for='shop_api' class="form-label my-2">Klucz API </label>
            <input type='text' name='shop_api' id='shop_api' class="form-control  my-2" required="required" placeholder='efb321aa'>
        </div>
        <div class="mb-3">
            <input type='hidden' name='mode' value='1'>
            <button type='submit' class='btn btn-success add-zestaw my-2'>Dodaj sklep</button>
        </div>
    </form>
    <form method='POST' class='col-3'>
    <h2>Połaczenie konta allegro</h2>
    <a class='btn btn-primary' href="https://allegro.pl/auth/oauth/authorize?response_type=code&client_id=<?=$clientId?>&redirect_uri=https://zestawy.o4s.pl/allegro.php">POŁACZ KONTO ALLEGRO</a>
    </form>
    <div class='col-5'>
        <h2>Zapisane sklepy Skyshop</h2>
        <table class='table table-striped'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nazwa</th>
                    <th>Klucz API</th>
                    <th>Link</th>
                    <th>Utworzono</th>
                    <th>Typ</th>
                    <th>Stan</th>
                    <th>btn</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM `connected_shop` WHERE `user_id`='".$user['user_id']."' ";
                $result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result)){
?>
                <tr>
                    <td><?=$row['shop_id']?></td>
                    <td><?=$row['shop_name']?></td>
                    <td><?=$row['shop_api']?></td>
                    <td><?=$row['shop_link']?></td>
                    <td><?=$row['shop_added_on']?></td>
                    <td><?=$row['shop_source']?></td>
                    <td>
                        <?php
$Params = [
    "function" => "getInvoices",
    "APIkey" => $row['shop_api']
    ];
    $ans = apiSkyShop($Params,$row['shop_link']);
    if($ans->response_code=='200'){
?>
                        <button class="btn btn-lg btn-success btn-sm" disabled>OK</button>
                        <?php
    }else{
        ?>
                        <button class="btn btn-lg btn-danger btn-sm" disabled>BŁĄD</button>
                        <?php
    }
?>


                    </td>
                    <td><button class='btn btn-warning btn-sm remove-shop tip' data-toggle="tooltip" data-placement="top" title="Usunięcie sklepu będzie skutkowało usunięciem wszystkich zestawów z naszej bazy" data-id='<?=$row['shop_id']?>'>USUŃ</button></td>
                </tr>
                <?php
}
            ?>
                <tr>
                </tr>
            </tbody>
        </table>
    </div>
    <div class='row border my-2 p-2 w-50'>
    <h2>Podłączone konta allegro</h2>
<?php

$sql = "SELECT * FROM `allegro_account` WHERE `user_id`=" . $_SESSION['user_id'] . " ";
$query = mysqli_query($conn, $sql);
?>
<table class='table table-striped table-bordered w-100'>
    <thead>
        <tr>
        <td class='p'>ID</td>
        <td class='p'>Login</td>
        <td class='p'>Ważne do</td>
        <td class='p'>Przyciski</td>
        </tr>
    </thead>
    <tbody>

        <?php
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        ?>
            <tr>
                <td><?= $row['allegro_id'] ?></td>
                <td><?=curlme($row['allegro_token'])->login?></td>
                <td><?= $row['allegro_expire'] ?></td>
                <td><button>a</button></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
</div>
    <div class='col-4'>
        <h2>Globalne ustawienia</h2>
            Ustawienia
    </div>
</div>

<?php

?>