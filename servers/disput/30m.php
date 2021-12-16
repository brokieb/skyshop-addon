<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include(dirname(__FILE__) . '/../functions.php');
include(dirname(__FILE__) . '/../conn.php');
inputLog("OKH ", 1, "1|disput");
$sql = "SELECT * FROM `allegro_account`";
$query = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
    $disputes = restGet('/sale/disputes', $row['allegro_token']);

    foreach ($disputes as $x) {
        foreach ($x as $z) {
            $a = restGet('/sale/disputes/' . $z->id . "/messages?limit=2", $row['allegro_token']);
            if ($z->messagesCount == 1 && count($a->messages) == 1) {
               
                inputLog("ODPISAŁEM KLIENTOWI " . $z->buyer->login . " ZE SKLEPU " . curlme($row['allegro_token'])->login . "NA PIERWSZĄ WIADOMOŚĆ ", 1, $row['user_id'] . "|disput");
                restPoXst("/sale/disputes/" . $z->id . "/messages", $row['allegro_token'], '{
            "text": "Witaj! Dziękujemy o poinfomowanie nas o zaistniałej sytuacji, nasz pracownik wkrótce się tym zajmie i postara się udzielić wyczerpującej odpowiedzi.",
            "type": "REGULAR"
            }');
            } elseif ($a->messages[0]->author->role == 'BUYER') {
                echo "ZGADZAA SIĘĘĘ";
                $NewDate = strtotime($a->messages[0]->createdAt ) + 60*60*2;
                $date = strtotime($a->messages[0]->createdAt*1);
                echo $NewDate."===".$date;
                if ($date > $NewDate) {
                    inputLog("ODPISAŁEM NA WIADOMOŚĆ KLIENTA " . $z->buyer->login . " ZE SKLEPU " . curlme($row['allegro_token'])->login . " PO 2 GODZINACH ", 1, $row['user_id'] . "|disput");
                   echo restPoXst("/sale/disputes/" . $z->id . "/messages", $row['allegro_token'], '{
            "text": "Witaj! Z uwagi na dużą ilość obsługiwanych zamówień nie możemy zająć się twoim zgłoszeniem tak szybko jakbyśmy tego chcieli, jednak możesz być pewny że odpowiemy najszybciej jak to możliwe.",
            "type": "REGULAR"
            }');
                }

                echo "</pre>";
            }
        }
    }
    if (strtotime(date('Y-m-d H:i:s'))*1 >= strtotime($row['allegro_expire']) - 60*60) {
        echo "ZMIANA ALLEGRO";
        //     echo "asd";
        $x = restPostAuth($row['allegro_refresh']);
        $myExpire = date("Y-m-d H:i:s", strtotime("+" . $x['expires_in'] . "sec - 5 min"));
        $sql = "
    UPDATE allegro_account SET
    allegro_token = '" . $x['access_token'] . "',
    allegro_refresh = '" . $x['refresh_token'] . "',
    allegro_expire = '" . $myExpire . "'
    WHERE allegro_id='" . $row['allegro_id'] . "'
    ";
        if (mysqli_query($conn, $sql)) {
            inputLog("Odświeżono token do  " . $myExpire, 1, $row['user_id'] . "|disput");
        } else {
            inputLog("Błąd przy aktualizacji w bazie nowego tokenu " . $sql, 2, $row['user_id'] . "|disput");
        }
    }
}
