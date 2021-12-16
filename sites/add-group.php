<?php
if(!defined('directly')){
    echo "not allowed :(";
  exit();  
}?>
            <form method='POST' class='col-3 add-zestaw ' id='form'>
                <div class="mb-3">
                    <label for='main-id' class="form-label my-2 tip" data-toggle="tooltip" data-placement="top" title="Podaj ID głównego produktu ze SS który będzie wystawiony jako zestaw">ID Głównego produktu</label>
                    <input type='number' name='main-id' class="form-control  my-2 clean-me" required="required">
                    <button type='button' class='btn btn-primary my-2' disabled='disabled'>PRODUKT NIE ISTNIEJE, UTWÓRZ
                        GO</button>
                </div>
                <div class="mb-3">
                    <label for='main-id' class="form-label my-2">Sklep</label>
                    <select class="form-control form-control-sm tip" data-toggle="tooltip" data-placement="top" title="Źródło z jakiego sklepu mają być poprane szczegóły o produkcie" name='shop-id'>

                    <?php
$sql = "SELECT * FROM `connected_shop` WHERE `user_id`=".$user['user_id']."; ";
$result = mysqli_query($conn, $sql);

while($row = mysqli_fetch_array($result)){
?>
<option value='<?=$row['shop_id']?>'><?=$row['shop_name']?> | <?=$row['shop_link']?></option>
<?php
}
                    ?>
  
</select>

                </div>
                <div class='border mb-3 p-3 together'>

                    <div class="mb-3">
                        <label for='main-id' class="form-label my-2 tip" data-toggle="tooltip" data-placement="top" title="ID produktu które podlega pod tworzony zestaw">ID dziecka</label>
                        <input type='number' name='child-id[]' class="form-control my-2 clean-me tip" data-toggle="tooltip" data-placement="top" title="Ile danego produktu będzie potrzebne do utworzenia jednego zestawu" required="required">
                    </div>
                    <div class="mb-3">
                        <label for='main-id' class="form-label">ilość w zestawie</label>
                        <input type='number' name='child-ilosc[]' class="form-control clean-me" required="required">
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check my-2 tip" data-toggle="tooltip" data-placement="top" title="Program automatycznie uzupełni cenę zakupu głównego produktu sumując wszystkie ceny zakupów produktów dzieci * ilość żeby zapobiec sprzedawaniu produktu po za małej cenie">
                        <input class="form-check-input" type="checkbox" name='no-b-price' value="1" id="no-b-price">
                        <label class="form-check-label" for="no-b-price">
                            NIE AKTUALIZUJ CENY ZAKUPU
                        </label>
                    </div>
                    <button type='button' class='btn btn-primary add-next-child my-2'>DODAJ KOLEJNE DZIECKO</button>
                    <button type='submit' class='btn btn-success my-2'>DODAJ ZESTAW</button>
                </div>
            </form>