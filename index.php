<?php
session_start();

//connectie met database
require_once 'includes/connect.php';


//er wordt gechekt of een gebruiker al is ingelogd
if (isset($_SESSION['username']) != '')
{
  header('location: home.php');
}

if(isset($_POST['name']) && isset($_POST['password']))
{

  $sql = sprintf("SELECT * FROM users WHERE username = '%s'",
      mysqli_real_escape_string($db, $_POST['name'])
  );

  $result = mysqli_query($db, $sql);

  $row = mysqli_fetch_assoc($result);

  if($row)
  {
    $hash = $row['password'];

    if(password_verify($_POST['password'], $hash))
    {
      if($row['user_role'] == 'admin')
      {
        $_SESSION['username'] = $_POST['name'];
        header('location: home.php');
      }
      else
      {
        $_SESSION['username'] = $_POST['name'];
        header('location: user.home.php');
      }
    }
    else
    {
      echo 'Combinatie klopt niet';
    }
  }
else
{
  echo 'Gebruiker bestaat niet';
}
}

mysqli_close($db);
?>
<!DOCTYPE HTML>
<html>
<head>
  <title>Inloggen</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300italic' rel='stylesheet' type='text/css'>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
</head>
<body>

<div class="loginform">
  <form method="post" action="">
    <div class="logologin">
      <img src="pictures/groenehart_logo.gif" width="300"/>
    </div>
    <div class="username">
      <input type="text" name="name" placeholder="Gebruikersnaam"/><br />
    </div>
    <div class="password">
      <input type="password" name="password" placeholder="Wachtwoord"/><br />
    </div>
    <div class="loginbutton">
      <button type="submit" name="submit" value="login">Inloggen</button>
    </div>
  </form>
</div>


</body>
</html>
