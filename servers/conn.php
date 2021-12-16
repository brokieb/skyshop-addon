
<?php

$conn = new mysqli("192.168.101.64","kajkosho_zestawy-skyshop","nyn%)[b%]8ZQ","kajkosho_skyshop_zestawy");
$clientId = "d60bbe2dfd4a47cc84f8aceefb0f676f";
$clientSecret = "3BF9b1Up3gIjGAwERFiWR9Et8ORhcF8djwa67vXLuhTa9e6iQqOhSYyQchQNwUPJ";
// Check connection
if (!mysqli_set_charset($conn, "utf8mb4")) {
  printf("Error loading character set utf8mb4: %s\n", mysqli_error($conn));
  exit();
}
if ($conn -> connect_errno) {
  echo "Failed to connect to MySQL: " . $conn -> connect_error;
  exit();
}

// include('functions.php');
?>