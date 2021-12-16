<!DOCTYPE html>
<html lang="pl">
<?php
$ver = "1.5";
?>

<head>
    <meta HTTP-EQUIV="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href='style/main.css?ver=<?= $ver ?>'>
    <link rel="stylesheet" href='style/all.min.css?ver=<?= $ver ?>'>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="js/lib/jquery.dataTables.min.js?ver=<?= $ver ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="js/lib/popper.min.js?ver=<?= $ver ?>"></script>
    <?php


    define('directly', true);
    include('conn.php');
    include('functions.php');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    ?>
</head>
<?php
if ((isset($_SESSION['user_id']) && isset($_SESSION['hash'])) || $_COOKIE['hash'] != NULL) {
    if (isset($_SESSION['user_id']) && isset($_SESSION['hash'])) {
        $sql = "SELECT * FROM `user` INNER JOIN `user_session` ON `user_session`.`user_id`=`user`.`user_id` WHERE `session_hash`='" . $_SESSION['hash'] . "' AND `user`.`user_id`='" . $_SESSION['user_id'] . "' LIMIT 1 ";
    } elseif ($_COOKIE['hash'] != NULL) {
        echo $sql = "SELECT * FROM `user` INNER JOIN `user_session` ON `user_session`.`user_id`=`user`.`user_id` WHERE `session_hash`='" . $_COOKIE['hash'] . "' AND `session_saved`=1 AND `session_expired` <= '" . Date('Y-m-d H:i:s') . "' LIMIT 1 ";
    } else {
        session_destroy();
        // header('location:index.php');
        exit();
    }
    if (isset($_SESSION['created'])) {
        if (time() - $_SESSION['created'] > 3600) {
            // session started more than 30 minutes ago
            // update creation time
            $last = $_SESSION['hash'];
            $_SESSION['hash'] = 1;
            $_SESSION['hash'] = $last;
            $_SESSION['created'] = time();
            if ($_POST['remember-me'] == 'on') {
                setcookie('hash', $hash,  time() + 86400 * 3);
            }
        }
    }

    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if (empty($user)) {
        session_destroy();
        setcookie('hash', NULL, time() - 3600, '/');
        header('location:index.php');
        exit();
    } else {
        echo "nie puste";
    }
}
?>

<body id="body-pd" class='bg-light'>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="tytul" aria-hidden="true">
        <div class="modal-dialog modal-lg ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tytul">tytuł</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <div class='nav-content'>
        <div class="l-navbar" id="nav-bar">
            <nav class="nav">
                <div>
                    <a href="#" class="nav_logo"> <i class="fab fa-mixcloud"></i>
                        <span class="nav_logo-name">SkyAddon</span>
                    </a>
                    <div class="nav_list">
                        <div class="accordion " id="accordionFlushExample">
                            <?php
                            if (!isset($user)) {
                                $prv = 0;
                            } else {
                                $prv = $user['user_privilege'];
                            }
                            $sql_group = "SELECT group_name,`nav_group`.group_id,GROUP_CONCAT(`nav_href`) as z FROM `nav_group` INNER JOIN `nav` ON `nav_group`.`group_id`=`nav`.`group_id` WHERE `group_privilege`<=" . $prv . " GROUP BY `group_id` ";
                            $result_group = mysqli_query($conn, $sql_group);
                            $allow = 0;
                            $site = "Nie masz uprawnień do tej zawartości :(";
                            while ($row_group = mysqli_fetch_array($result_group, MYSQLI_ASSOC)) {
                                if (strpos($row_group['z'], $_GET['site']) !== false) {
                                    $expended = 'true';
                                    $show = "show";
                                    $active = 'actived';
                                } else {
                                    $expended = 'false';
                                    $show = "";
                                    $active = 'collapsed ';
                                }
                            ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-heading<?= $row_group['group_id'] ?>">
                                        <button class="accordion-button <?= $active ?>" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?= $row_group['group_id'] ?>" aria-expanded=<?= $expended ?> aria-controls="flush-collapse<?= $row_group['group_id'] ?>">
                                            <?= $row_group['group_name'] ?>
                                        </button>
                                    </h2>

                                    <div id="flush-collapse<?= $row_group['group_id'] ?>" class="accordion-collapse collapse <?= $show ?>" aria-labelledby="flush-heading<?= $row_group['group_id'] ?>" data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body">
                                            <ul>


                                                <?php


                                                $sql = "SELECT * FROM `nav` WHERE `nav_privilege`<=" . $prv . " AND (`nav_force`=" . $prv . " OR `nav_force` IS NULL) AND `group_id`=" . $row_group['group_id'] . " ;";
                                                $result = mysqli_query($conn, $sql);

                                                while ($row = mysqli_fetch_array($result)) {
                                                    if (!isset($_GET['site'])) {
                                                        header('location:index.php?site=' . $row['nav_href']);
                                                        exit;
                                                    }

                                                    if ($row['nav_show'] == 1) {
                                                ?>
                                                        <li>
                                                            <?php
                                                            if ($_GET['site'] == $row['nav_href']) {
                                                            ?>
                                                                <a href="?site=<?= $row['nav_href'] ?>" class="nav_link active">
                                                                    <i class="fas <?= $row['nav_icon'] ?>"></i>
                                                                    <span class="nav_name"><?= $row['nav_title'] ?></span>
                                                                </a>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <a href="?site=<?= $row['nav_href'] ?>" class="nav_link">
                                                                    <i class="fas <?= $row['nav_icon'] ?>"></i>
                                                                    <span class="nav_name"><?= $row['nav_title'] ?></span>
                                                                </a>
                                                            <?php
                                                            }
                                                            ?>
                                                        </li>
                                                <?php
                                                    }
                                                    if ($_GET['site'] == $row['nav_href']) {
                                                        //strona dozwolona
                                                        $allow = 1;
                                                        $site = $row['nav_title'];
                                                    }
                                                }

                                                ?>

                                            </ul>
                                        </div>

                                    </div>

                                </div>
                            <?php
                            }

                            ?>
                        </div>
                    </div>
                </div>
        <div>
            <a href="instrukcja.pdf" target="_blank" class="nav_link tooltip-toggle"> <i class="fas fa-file-pdf"></i> <span class="nav_name">Instrukcja obsługi</span>
                <a href="#" class="nav_link text-danger tooltip-toggle"> <i class="fas fa-hands-helping"></i>
                    <span class="nav_name">Dymki pomocy</span>
                </a>
                <?php
                if (isset($user)) {
                ?>
                    <a href="?site=settings" class="nav_link"><i class="fas fa-cogs"></i> <span class="nav_name">Ustawienia</span>
                    </a>
                    <a href="?site=profile" class="nav_link"><i class="far fa-address-card"></i> <span class="nav_name"><?= $user['user_email'] ?></span>
                    </a>
                    <a href="destroy.php" class="nav_link"> <i class="fas fa-power-off"></i> <span class="nav_name">Wyloguj się</span>
                    </a>

                <?php

                }
                ?>

                </nav>
        </div>






        <header class="header" id="header">
            <div class="header_toggle "> <i class='fas fa-bars' id="header-toggle"></i> </div>
            <H2 class='m-0 p-3' id='pageTitle'><?= $site ?></H2>
        </header>
    </div>
    <!--Container Main start-->
    <div style='min-height:90vh' style='padding-bottom:50px;'>
        <?php
        if ($allow == 1) {
            if (file_exists('sites/' . $_GET['site'] . '.php')) {
                include('sites/' . $_GET['site'] . '.php');
            } else {
                include($_GET['site'] . '.php');
            }
        } else {
        ?>
            <p>Spróbuj przejść do strony głównej <a href='index.php'>TUTAJ</a>, jeżeli to nie pomoże skontaktuj się z
                administratorem strony pod adresem brokieb@gmail.com</p>
        <?php
        }
        ?>


    </div>
    <?php
    if (isset($_POST['mode'])) {
        switch ($_POST['mode']) {
            case 1:

                $sql2 = "SELECT COUNT(`shop_id`) as sumka FROM `connected_shop` WHERE `user_id`='" . $user['user_id'] . "'";
                $query2 = mysqli_query($conn, $sql2);
                $row2 = mysqli_fetch_array($query2, MYSQLI_ASSOC);

                $limit = 0;
                $wiel = checkLimit($row2['sumka'], 1);
                if ($wiel != 0) {

                    $_SESSION['alerts'][$uid = uniqid()] = array(
                        'add' => time(),
                        'uid' => $uid,
                        'type' => 'danger',
                        'subject' => 'Uwaga!',
                        'value' => 'Osiągnąłeś limit dodanych sklepów!'
                    );
                    header('location:index.php?site=' . $_GET['site']);
                } else {



                    echo $sql = "INSERT INTO `connected_shop`(`shop_name`,`user_id`, `shop_link`, `shop_api`,`shop_source`) VALUES ('" . $_POST['friendly_name'] . "','" . $user['user_id'] . "','" . $_POST['shop_link'] . "','" . $_POST['shop_api'] . "','SKYSHOP')";
                    if (mysqli_query($conn, $sql)) {

                        $_SESSION['alerts'][$uid = uniqid()] = array(
                            'add' => time(),
                            'uid' => $uid,
                            'type' => 'success',
                            'subject' => 'Sukces! ',
                            'value' => 'Sklep został poprawnie połączony!'
                        );
                        header('location:index.php?site=' . $_GET['site']);
                    }
                }
                break;
            case 2:
                if ($_POST['password'] == $_POST['rpassword']) {
                    $sql2 = "SELECT user_id FROM `user` WHERE `user_email`='" . $_POST['email'] . "'; ";
                    $query2 = mysqli_query($conn, $sql2);
                    $row2 = mysqli_fetch_array($query2, MYSQLI_ASSOC);
                    if (empty($row2)) {

                        echo $sql = " INSERT INTO `user`(`user_email`, `user_password`, `user_privilege`, `bundle_id`) VALUES ('" . $_POST['email'] . "','" . password_hash($_POST['password'], PASSWORD_DEFAULT) . "',1,1);";

                        if (mysqli_query($conn, $sql)) {

                            $_SESSION['alerts'][$uid = uniqid()] = array(
                                'add' => time(),
                                'uid' => $uid,
                                'type' => 'success',
                                'subject' => 'Sukces!',
                                'value' => 'Konto zostało poprawnie założone, możesz się zalogować'
                            );
                            header('location:index.php?site=' . $_GET['site']);
                        }
                    } else {

                        $_SESSION['alerts'][$uid = uniqid()] = array(
                            'add' => time(),
                            'uid' => $uid,
                            'type' => 'warning',
                            'subject' => 'błąd!',
                            'value' => 'Konto o podanym loginie istnieje, jeżeli nie pamiętasz hasła skontaktuj się z administratorem'
                        );
                        header('location:index.php?site=' . $_GET['site']);
                    }
                } else {

                    if (mysqli_query($conn, $sql)) {

                        $_SESSION['alerts'][$uid = uniqid()] = array(
                            'add' => time(),
                            'uid' => $uid,
                            'type' => 'danger',
                            'subject' => ':(',
                            'value' => 'Podane hasła różnią się od siebie!'
                        );
                        header('location:index.php?site=' . $_GET['site']);
                    }
                }
                break;
            case 3:
                $sql = "INSERT INTO `buy_history`( `buy_quantity`, `buy_status`, `bundle_id`, `user_id`) VALUES ('1','PAID','" . $_POST['bundle_id'] . "','" . $_POST['user_id'] . "');";
                $sql .= "UPDATE `user` SET `bundle_id`='" . $_POST['bundle_id'] . "' WHERE `user_id` = '" . $_POST['user_id'] . "'; ";
                if (mysqli_multi_query($conn, $sql)) {

                    $_SESSION['alerts'][$uid = uniqid()] = array(
                        'add' => time(),
                        'uid' => $uid,
                        'type' => 'success',
                        'subject' => 'Sukces!',
                        'value' => 'Poprawnie zmieniono aktualny pakiet klienta!!!'
                    );
                    header('location:index.php?site=' . $_GET['site']);
                }
                echo $sql;
                break;
            case 4:
                echo $sql = "INSERT INTO `mail_users`(`email`,`Username`, `Password`, `HostSMTP`, `HostIMAP`,`catalog`) VALUES 
                    ('" . $_POST['email'] . "','" . $_POST['username'] . "','" . $_POST['password'] . "','" . $_POST['smtp'] . "','" . $_POST['imap'] . "','" . $_POST['catalog'] . "')";
                if (mysqli_query($conn, $sql)) {
                    echo "<script>";
                    echo "alert('WRZUCONO KONTO');";
                    echo "window.location.href = 'index.php'";
                    echo "</script>";
                } else {
                    echo "BŁĄD";
                }
                break;
            case 5:
                echo $sql = "DELETE FROM mail_users WHERE id_user='" . $_POST['id_user'] . "'";

                if (mysqli_query($conn, $sql)) {
                    echo "<script>";
                    echo "alert('USUNIĘTO POPRAWNIE ID " . $_POST['id_user'] . "');";
                    echo "window.location.href = 'index.php'";
                    echo "</script>";
                } else {
                    echo "BŁĄD";
                }
                break;
            case 6:
                echo $sql = "UPDATE `mail_template` SET 
                            `template`='" . $_POST['template'] . "',
                            `AltBody`='" . $_POST['AltBody'] . "'
                             WHERE `id_template`='" . $_POST['temp-id'] . "'";
                if (mysqli_query($conn, $sql)) {
                    echo "<script>";
                    echo "alert('Poprawnie zmieniono treść wiadomości');";
                    echo "window.location.href = 'index.php'";
                    echo "</script>";
                } else {
                    echo "BŁĄD";
                }
                break;
            case 7:
                echo $sql = "UPDATE `mail_users` SET 
                            `mail_status`='" . $_POST['setting'] . "' WHERE `id_user`='" . $_POST['id_user'] . "'";
                if (mysqli_query($conn, $sql)) {
                    echo "<script>";
                    echo "alert('Poprawnie zmieniono ustawienie');";
                    echo "window.location.href = 'index.php'";
                    echo "</script>";
                } else {
                    echo "BŁĄD";
                }
                break;
            case 8:
                echo $sql = "UPDATE `mail_users` SET
                            `Password`='" . $_POST['n-password'] . "' WHERE `id_user`='" . $_POST['id_user'] . "'";
                if (mysqli_query($conn, $sql)) {
                    echo "<script>";
                    echo "alert('Poprawnie zmieniono hasło');";
                    echo "window.location.href = 'index.php'";
                    echo "</script>";
                } else {
                    echo "BŁĄD";
                }
                break;
            case 9:
                echo $sql = "UPDATE `mail_users` SET 
                            `mail_status`='-1' WHERE 1=1";
                if (mysqli_query($conn, $sql)) {
                    echo "<script>";
                    echo "alert('Zatrzymano wszystko');";
                    echo "window.location.href = 'index.php'";
                    echo "</script>";
                } else {
                    echo "BŁĄD";
                }
                break;
            case 10:
                echo $sql = "INSERT INTO `mail_template`(`template`,`AltBody`, `mail_status`, `Subject`
                            ) VALUES 
                    (
                        '" . $_POST['template'] . "',
                        '" . $_POST['AltBody'] . "',
                        '0',
                        '" . $_POST['subject'] . "'
                
                    )";
                if (mysqli_query($conn, $sql)) {
                    echo "<script>";
                    echo "alert('Pomyślnie wrzucono nowy template');";
                    echo "window.location.href = 'index.php'";
                    echo "</script>";
                } else {
                    echo "BŁĄD";
                }
                break;
            case 11:
                $sql = "SELECT `allegro_token` FROM `allegro_account` WHERE `user_id`=" . $_SESSION['user_id'] . " AND `allegro_id`=" . $_POST['sklep'] . " ";
                $query = mysqli_query($conn, $sql);
                $token = mysqli_fetch_array($query, MYSQLI_ASSOC);
                if (isset($_POST['close'])) {
                    $type = "END_REQUEST";
                } else {
                    $type = "REGULAR";
                }
                $ans = restPoXst("/sale/disputes/" . $_POST['id'] . "/messages", $token['allegro_token'], '{
                                "text": "' . $_POST['comment'] . '",
                                "type": "' . $type . '"
                                }');
                print_r('{
                                    "text": "' . str_replace("\n", "\\n", $_POST['comment']) . '",
                                    "type": "' . $type . '"
                                    }');
                print_r($_POST);
                print_r($token);
                print_r($ans);
                $_SESSION['alerts'][$uid = uniqid()] = array(
                    'add' => time(),
                    'uid' => $uid,
                    'type' => 'success',
                    'subject' => 'Sukces!',
                    'value' => 'Poprawnie wysłano odpowiedź do klienta!!!' . $ans
                );
                // header('location:index.php?site=' . $_GET['site']);
                echo "</script>";
                break;
        }
    }



    ?>
    <div class='p-absolute' style='margin-left:80px'>2021 | DW</div>
    <div class='position-fixed w-25 m-1 alerts' style='right:25px;bottom:110px;'>
        <?php
        if (isset($_SESSION['alerts'])) {
            foreach ($_SESSION['alerts'] as $z) {
                if (time() - $z['add'] < 30) {
        ?>
                    <div class="alert alert-<?= $z['type'] ?> alert-dismissible fade show" role="alert" id='myAlert'>
                        <strong><?= $z['subject'] ?></strong> <?= $z['value'] ?>
                        <button type="button" class="btn-close close close-alert" data-id='<?= $z['uid'] ?>' data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

        <?php
                } else {
                    unset($_SESSION['alerts'][$z['uid']]);
                }
            }
        }
        ?>

    </div>
    <script src='js/nav.js'></script>
    <script src='js/main.js'></script>
    <script src='js/ajax.js'></script>
    <script src='js/this-group-details.js'></script>
    <script src="//code.tidio.co/uomlui4isxvb7pswjnlszh47sivjcyjs.js" async></script>
</body>

</html>