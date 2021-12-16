<?php
include('conn.php');
function apiSkyShop($apiParams,$shop){
    // $apiParams = [
    //     "function" => "getProducts",
    //     "APIkey" => "6bb1a475",
    //     "start" => $_GET['from'],
    //     "limit" => $_GET['to'],
    //     "search"=> implode($arr,"[AND]").$hurt
    // ];
    
    $curl = curl_init("https://".$shop);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($apiParams));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    return $ans = json_decode($response);
}
function array_to_xml( $data, &$xml_data ) {
    foreach( $data as $key => $value ) {
        if( is_array($value) ) {
            $subnode = $xml_data->addChild("item");
            array_to_xml($value, $subnode);
        } else {
            $xml_data->addChild("$key",htmlspecialchars("$value"));
        }
     }
}
function inputLog($tekst,$typ,$user){
    switch($typ){
        case 0:
            $status = "[NULL] ";
        break;
        case 1:
            $status = "[OK] ";
        break;
        case 2:
            $status = "[WAR] ";
        break;
        case 3:
            $status = "[ERR] ";
        break;
    }
    $data = "[".date("d-m-Y H:i")."]".$status.$tekst."\n </Br>";
      echo  $file = dirname(__FILE__)."/logs/".$user.".txt";
        $fp = fopen($file, "a+");
        fwrite($fp, $data);
            fclose($fp);
            echo $data;
    }

    function checkLimit($sumka,$wiel){
include('conn.php');
$sql_t = "SELECT * FROM `user` LEFT JOIN `bundle` ON `user`.`bundle_id`=`bundle`.`bundle_id` WHERE `user`.`user_id`='".$_SESSION['user_id']."' ";
$query = mysqli_query($conn,$sql_t);
$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
$roznica = $row['bundle_max_groups']-$sumka;
$limit = 0;
if($roznica<$wiel){
   return $roznica;

}else{
    return 0;
}

    }



    function curlme($token){
    $getCategoriesUrl = "https://api.allegro.pl/me";
    $ch = curl_init($getCategoriesUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization:bearer $token",
        "Accept: application/vnd.allegro.public.v1+json",
    ]);
    $mainCategoriesResult = curl_exec($ch);
    $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($mainCategoriesResult === false || $resultCode !== 200) {
        return "!#";
    } else {
        $categoriesList = json_decode($mainCategoriesResult);
        return $categoriesList;
    }
}

function restPut($event,$token){


    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.allegro.pl'.$event);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{"status": "SENT"}');
    
    $headers = array();
    $headers[] = 'Authorization: Bearer '.$token;
    $headers[] = 'Accept: application/vnd.allegro.public.v1+json';
    $headers[] = 'Content-Type: application/vnd.allegro.public.v1+json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $mainResult = curl_exec($ch);
    $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($mainResult === false || $resultCode !== 204) {
        return "BŁĄD ".$resultCode;
    } else {
        $answer = json_decode($mainResult);
        return $answer;

}



}
function restGet($event,$token){
    $url = "https://api.allegro.pl".$event;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization:bearer $token",
        "Accept: application/vnd.allegro.public.v1+json",
    ]);
    $mainResult = curl_exec($ch);
    $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($mainResult === false || $resultCode !== 200) {
        return $resultCode;
    } else {
        $answer = json_decode($mainResult);
        return $answer;
    }
}

function restPoXst($event,$token,$json){
    $ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.allegro.pl".$event);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
$headers = array();
$headers[] = 'Authorization: Bearer '.$token;
$headers[] = 'Accept: application/vnd.allegro.public.v1+json';
$headers[] = 'Content-Type: application/vnd.allegro.public.v1+json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    $mainResult = curl_exec($ch);
    $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($mainResult === false || $resultCode !== 200) {
        echo "X";
        return $resultCode;
    } else {
        $answer = json_decode($mainResult);
        return $answer;
    }
}


function restPostAuth($reftoken){
    include('conn.php');
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://allegro.pl/auth/oauth/token?grant_type=refresh_token&refresh_token='.$reftoken.'&https://zestawy.o4s.pl/allegro.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = array();
    $base64 = $clientId.":".$clientSecret;
    $headers[] = "Authorization:Basic ".base64_encode($base64);
    $headers[] = 'Accept: application/vnd.allegro.public.v1+json';
    $headers[] = 'Content-Type: application/vnd.allegro.public.v1+json';
    curl_setopt($ch, CURLOPT_HTTPHEADER , $headers);
    $mainResult = curl_exec($ch);
    $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($mainResult === false || $resultCode !== 200) {
        return "BŁĄD ".$mainResult;
    
    } else {
       

       return json_decode($mainResult,true);

    }

}
?>