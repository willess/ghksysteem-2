<?php
//sessie wordt gestart
session_start();

//er wordt een connectie gemaakt met de database
require_once 'includes/connect.php';

//checken of een gebruiker is ingelogd
require_once 'includes/usercheck.php';

$login_user = $row['username'];
$user_id = $row['user_id'];
//is er niemand ingelogd, dan wordt deze naar de inlogpagina gestuurd.
if(!isset($user_check))
{
  header('location: index.php');
}

$query = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($db, $query);

$info = [];
while($row2 = mysqli_fetch_assoc($result))
{
  $info[] = $row2;
}
if(isset($_POST['submit']))
{
  $password1 = $_POST['password1'];
  $password2 = $_POST['password2'];
  $hash = password_hash($password1, PASSWORD_DEFAULT);

  if($password1 == $password2)
  {
    $sql = sprintf("UPDATE users SET password = '$hash' WHERE user_id = '$user_id'
      ",
        mysqli_real_escape_string($db, $hash));
        mysqli_query($db, $sql);
        echo 'Wachtwoord is gewijzigd';
  }
  else
  {
    echo 'Wachtwoord komt niet overeen';
  }
}

?>
<!DOCTYPE HTML>
<html>
<head>
  <title>Mijn gegevens</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300italic' rel='stylesheet' type='text/css'>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
</head>
<body>

<main>
  <form method="post" action="">
    <h3>Verander je Wachtwoord</h3>
    <p>Om je wachtwoord te wijzigen moeten beide velden overeenkomen!</p>
    <div class="">
      <label>Je nieuwe wachtwoord:</label>
      <input type="password" name="password1" value=""/><br />
    </div>
    <div class="">
      <label>Herhaal wachtwoord:</label>
      <input type="password" name="password2" value=""/><br />
    </div>
    <div class="loginbutton">
      <input type="submit" name="submit" value="Wijzig" />
    </div>
  </form>
</main>
</body>
</html>
