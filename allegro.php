 <?php
include "conn.php";

function getAccessToken(): String
{
    include "conn.php";
$authUrl = "https://allegro.pl/auth/oauth/token?grant_type=client_credentials";


$ch = curl_init($authUrl);

curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERNAME, $clientId);
curl_setopt($ch, CURLOPT_PASSWORD, $clientSecret);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$tokenResult = curl_exec($ch);
$resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($tokenResult === false || $resultCode !== 200) {
exit ("Something went wrong!!a");
}

$tokenObject = json_decode($tokenResult);

return $tokenObject->access_token;
}

function main()
{
    $token = getAccessToken();

}
main();


function second($token): stdClass
{
    include "conn.php";
    $getCategoriesUrl = "https://allegro.pl/auth/oauth/token";

    $ch = curl_init($getCategoriesUrl);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                 "Authorization:Basic ".$token,
                 "Accept: application/vnd.allegro.public.v1+json"
    ]);

    $mainCategoriesResult = curl_exec($ch);
    $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($mainCategoriesResult === false || $resultCode !== 200) {
        exit ("Something went wronga");
    }

    $categoriesList = json_decode($mainCategoriesResult);

    return $categoriesList;
}











if(isset($_GET['code'])){

function curlpost(){
    include "conn.php";
    $ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"https://allegro.pl/auth/oauth/token?grant_type=authorization_code&code=".$_GET['code']."&redirect_uri=https://zestawy.o4s.pl/allegro.php");
curl_setopt($ch, CURLOPT_POST, 1);
echo $base64 = $clientId.":".$clientSecret; //CLIENT_ID:CLIENT_SECRET
curl_setopt($ch, CURLOPT_HTTPHEADER, [//autoryzacja BASE 64
    "Authorization:Basic ".base64_encode($base64),
    "Accept: application/vnd.allegro.public.v1+json"
]);

// In real life you should use something like:
// curl_setopt($ch, CURLOPT_POSTFIELDS, 
//          http_build_query(array('postvar1' => 'value1')));

// Receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);
return $server_output;
curl_close ($ch);
}

$x = curlpost($_GET['code']);
$y = json_decode($x);

}
print_r($y);
$myToken = $y->access_token;
$myRefresh = $y->refresh_token;
$myExpire = date("Y-m-d H:i", strtotime("+".$y->expires_in."sec - 5 min"));
$sql = "
INSERT INTO `allegro_account`(`allegro_token`, `allegro_refresh`, `user_id`, `allegro_expire`) VALUES ('".$myToken."','".$myRefresh."','".$_SESSION['user_id']."','".$myExpire."')
";
if ($conn->query($sql) === TRUE) {
echo '<script type="text/javascript">';
echo 'alert("Połączono poprawnie konto z allegro!");';
echo 'window.location.href = "index.php?site=settings"';
echo '</script>';
print_r($_POST);
} else {
echo "Error: " . $sql . "<br>" . $conn->error;
}




?>




