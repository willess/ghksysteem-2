<?php

//gebruikersnaam van de sessie wordt in een variabale gezet
$user_check = $_SESSION['username'];

$sql = mysqli_query($db, "SELECT * FROM users WHERE username = '$user_check'");

$row = mysqli_fetch_array($sql, MYSQLI_ASSOC);

?>
