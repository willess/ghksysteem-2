<?php

//er wordt connectie gemaakt met de database
$host = 'sql.hosted.hro.nl';
$user = '0908405';
$password = 'taijaigh';
$database = '0908405';

$db = mysqli_connect($host, $user, $password, $database) or die("error: " . mysqli_connect_error());

?>
