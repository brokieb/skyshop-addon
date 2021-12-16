
<?php

$conn = new mysqli("192.168.101.64","kajkosho_zestawy-skyshop","nyn%)[b%]8ZQ","kajkosho_skyshop_zestawy");
//dane logowania allegro
$clientId = "d60bbe2dfd4a47cc84f8aceefb0f676f";
$clientSecret = "3BF9b1Up3gIjGAwERFiWR9Et8ORhcF8djwa67vXLuhTa9e6iQqOhSYyQchQNwUPJ";
//koniec danych allegro

// Check connection
if (!mysqli_set_charset($conn, "utf8mb4")) {
  printf("Error loading character set utf8mb4: %s\n", mysqli_error($conn));
  exit();
}
if ($conn -> connect_errno) {
  echo "Failed to connect to MySQL: " . $conn -> connect_error;
  exit();
}
session_set_cookie_params(60 * 60 * 24 * 7);
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
    session_start();
// include('functions.php');
?>