<?php
//sessie wordt gestart
session_start();

//er wordt een connectie gemaakt met de database
require_once 'includes/connect.php';

//er wordt gechekt of een gebruiker al is ingelogd
require_once 'includes/usercheck.php';

if(!isset($user_check))
{
  header('location: edit.php');
}

if($row['user_role'] !== "admin")
{
  header('location: user.home.php');
}

if(isset($_POST['submit']))
{
  $username = $_POST['username'];
  $password = $_POST['password'];
  $userrole = $_POST['user_role'];

  $hash = password_hash($password, PASSWORD_DEFAULT);

  $sql = sprintf("INSERT INTO users(username, password, user_role) VALUES (
'$username', '$hash', '$userrole'
)",
  mysqli_real_escape_string($db, $username),
  mysqli_real_escape_string($db, $hash),
  mysqli_real_escape_string($db, $userrole));

  mysqli_query($db, $sql);
  mysqli_close($db);

  echo 'user added';
}

mysqli_close($db);
?>
<!DOCTYPE HTML>
<html>
<head>
  <title>Gebruiker aanmaken</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300italic' rel='stylesheet' type='text/css'>
</head>
<body>

<header>
  <nav>
    <ul>
      <?php if($row['user_role'] == 'admin') { ?>

      <li><a href="home.php">Home</a></li>
      <li><a href="create.php">Gebruiker aanmaken</a></li>
<?php } ?>
      <ul style="float:right;list-style-type:none;">
        <li><a>Hallo <?php echo $user_check; ?></a></li>
        <li><a href="personalinfo.php">Gegevens</a></li>
        <li><a href="logout.php">Uitloggen</a> </li>
      </ul>
    </ul>
  </nav>
</header>

<main>
  <div class="loginform">
  <form method="post" action="">
      <div class="logologin">
        <img src="pictures/groenehart_logo.gif" width="300"/>
      </div>
      <div class="">
        <input type="text" name="username" placeholder="gebruikersnaam"/><br />
      </div>
      <div class="">
        <input type="password" name="password" placeholder="wachtwoord"/><br />
      </div>
      <div class="">
        <select type="select" name="user_role">
          <option value="user">gebruiker</option>
          <option value="admin">admin</option>
        </select><br /><br />
      </div>
      <div class="loginbutton">
        <input type="submit" name="submit" value="toevoegen" />
      </div>
    </div>
  </form>

</main>

</body>
</html>
