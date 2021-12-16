<div class='row border w-50 my-2'>
    <h2>Podłącz nowe konto</h2>
    <form method='post' class='w-50'>
        <h5>Nowy email</h5>
        <div class='mb-3'>
            <label class="form-label" for='username'>adres Email allegro</label>
            <input class="form-control" type='text' name='email'>
        </div>
        <div class='mb-3'>
            <label class="form-label" for='username'>Ładna nazwa</label>
            <input class="form-control" type='text' name='username'>
        </div>
        <div class='mb-3'>
            <label class="form-label" for='password'>Hasło do maila</label>
            <input class="form-control" type='text' name='password'>
        </div>
        <div class='mb-3'>
            <label class="form-label" for='host'>Serwer SMTP</label>
            <input class="form-control" type='text' name='smtp'>
        </div>
        <div class='mb-3'>
            <label class="form-label" for='host'>Serwer IMAP</label>
            <input class="form-control" type='text' name='imap'>
        </div>
        <div class='mb-3'>
            <label class="form-label" for='host'>Katalog z mailami</label>
            <input class="form-control" type='text' name='catalog'>
        </div>
        <div class='mb-3'>
            <input class="form-control" type='hidden' name='mode' value='4'>
            <button type='submit' class='btn btn-primary'>WYŚLIJ</button>
        </div>
    </form>
</div>

<div class='row border my-2 p-2 w-75'>
    <h2>Podłączone konta</h2>
    <?php
    $sql = "SELECT * FROM mail_users WHERE `user_id`=".$_SESSION['user_id']." ";
    $query = mysqli_query($conn, $sql);
    ?>
    <table class='table table-striped'>
        <tr>
            <td>ID</td>
            <td>Email</td>
            <td>Ładna nazwa</td>
            <td>Hasło</td>
            <td>SMTP</td>
            <td>IMAP</td>
            <td>Katalog</td>
            <td>Filtr</td>
            <td>Stan</td>
            <td>Usuń</td>
        </tr>
        <?php
        while ($row = mysqli_fetch_array($query)) {
        ?>
            <tr>
                <td><?= $row['id_user'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['username'] ?></td>
                <td>
                    <form method='post'>
                        <input type='hidden' name='mode' value='8'>
                        <input type='hidden' name='id_user' value='<?= $row['id_user'] ?>'>
                        <input type='text' name='n-password'>
                        <input type='submit' value='Aktualizuj hasło'>
                    </form>

                </td>
                <td><?= $row['HostSMTP'] ?></td>
                <td><?= $row['HostIMAP'] ?></td>
                <td><?= $row['catalog'] ?></td>
                <td>
                    <form method='POST'>
                        <input type='hidden' name='id_user' value='<?= $row['id_user'] ?>'>
                        <input type='hidden' name='mode' value='7'>
                        <select name='setting' id='sett'>
                            <?php
                            for ($i = -1; $i <= 2; $i++) {

                                if ($row['mail_status'] == $i) {
                                    echo "<option value='" . $i . "' selected>" . $i . "</option>";
                                } else {
                                    echo "<option value='" . $i . "'>" . $i . "</option>";
                                }
                            }
                            ?>
                        </select>
                        <input type='submit' value='AKTUALIZUJ'>

                    </form>
                </td>
                <td>
                    <form method='POST'>
                        <input type='hidden' name='id_user' value='<?= $row['id_user'] ?>'>
                        <input type='hidden' name='mode' value='5'>
                        <input type='submit' value='USUŃ'>
                    </form>
                </td>
            </tr>

        <?php
        }
        ?>
</div>

<?php


?>