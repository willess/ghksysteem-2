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

//datum en tijd worden in een variabele gezet
require_once 'includes/date.php';

if(isset($_POST['morgen'])){
  $datumToday = $datumTomorrow;
  $datumTodayNL = $datumTomorrowNL;
}

//alle gegevens uit ritten met de waarde van de ingelogde gebruiker en de datum van vandaag worden opgehaald
$queryAll = "SELECT * FROM ritten WHERE user_id = '$user_id' AND datum = '$datumToday'";
$result = mysqli_query($db, $queryAll);

//$autoritnummer = mysqli_fetch_assoc($result);

$ritten = [];
while($row = mysqli_fetch_assoc($result))
{
  $ritten[] = $row;
}

//auto + begintijd
$query = "SELECT autonummer, begintijd FROM ritten WHERE user_id='$user_id' AND datum = '$datumToday'";
$result2 = mysqli_query($db, $query);
$row = mysqli_fetch_assoc($result2);

//als er op submit wordt gedrukt
if(isset($_POST['submit']))
{
  $checked = $_POST['checked'];
  $tekstuser = $_POST['tekstuser'];

  //er wordt geloopt door alle gecheckte ritten
  foreach($checked as $key => $value) {
    if(empty($value['geweest'])) {
      //ritten worden geupdate als ze zijn gecheckt
      $query = sprintf( "UPDATE ritten SET geweest = '$time', tekstuser = '$tekstuser[$key]'
                WHERE user_id = '$user_id' AND datum = '$datumToday' and id = $value and geweest = ''",
      mysqli_real_escape_string($db, $time),
      mysqli_real_escape_string($db, $tekstuser));
      $result = mysqli_query($db, $query);
    }
  }
  header('Location: '.$_SERVER['REQUEST_URI']);
}

mysqli_close($db);
?>
<!DOCTYPE HTML>
<html>
<head>
  <title>Het Groene Hart Koeriers</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300italic' rel='stylesheet' type='text/css' />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
</head>
<body>
<header>
  <nav>
    <ul>
      <ul style="float:right;list-style-type:none;">
        <li><a href="user.home.php">Hallo <?php echo $user_check; ?></a></li>
        <li><a href="personalinfo.php">Gegevens</a></li>
        <li><a href="logout.php">Uitloggen</a> </li>
      </ul>
    </ul>
  </nav>
</header>

<main>
  <div class="vm">
    <form method="post" action="<?php $_SERVER['REQUEST_URI']; ?>">
      <input type="submit" name="vandaag" value="Vandaag" />
    </form>
  <form method="post" action="<?php $_SERVER['REQUEST_URI']; ?>">
    <input type="submit" name="morgen" value="Morgen" />
  </form>
  </div>

  <div class="usertable">
    <form method="post" action="">
    <table cellspacing="0" border="0">
<tr class="start">
  <th style="width: 37%"><?= $datumTodayNL; ?></th>
  <th style="width: 37%">Tijd: <?= $row['begintijd']; ?>  uur</th>
  <th style="width: 17%"></th>
  <th style="width: 13%">auto</th>
</tr>

      <?php
      if($datumToday) {
        foreach ($ritten as $key => $value) { ?>
          <tr>
            <td style="width: 40%"><?php echo $value['opdrachtgever']; ?></td>
            <td style="width: 40%"><?php echo $value['plaats']; ?></td>
            <td style="width: 20%"><?php echo $value['ladenlossen']; ?></td>
            <td><?php echo $value['autonummer']; ?></td>
            </tr>
            <tr>
            <td style="width: 40%"><?php echo $value['omschrijving']; ?></td>
            <td style="width: 40%"><?php echo $value['bijzonderheden']; ?></td>
              <td></td>
            <td style="width: 20%"><input id="checkbox" type="checkbox" name="checked[]" value="<?=$value['id']?>" <?= (empty($value['geweest']) == '')? 'checked' : ''; ?>/></td>
              <tr>
                <td><input class="tekstuser" type="text" name="tekstuser[]" placeholder="Bijzonderheden" style="width: 100%" maxlength="250" value="<?php echo $value['tekstuser']; ?>" /></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
          </tr>

        <?php }
      }
      ?>

    </table>
      <div class="sendUser">
        <input type="submit" name="submit" value="Verzend" />
      </div>
    </form>
  </div>
</main>

</body>
</html>
