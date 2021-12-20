
<?php
$IP = "localhost";
$DB = "DB";
$PW = "DB_PASSWORD";
$clientId = "ALLEGRO CLIENT ID";
$clientSecret = "ALLEGRO SECRET";

$conn = new mysqli($IP,$DB,$PW,$DB);
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
