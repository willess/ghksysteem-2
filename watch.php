<?php
//sessie wordt gestart
session_start();

//er wordt een connectie gemaakt met de database
require_once 'includes/connect.php';

//checken of een gebruiker is ingelogd
require_once 'includes/usercheck.php';

$login_user = $row['username'];

//is er niemand ingelogd dan wordt deze naar de inlogpagina gestuurd.
if(!isset($user_check))
{
  header('location: index.php');
}

if($row['user_role'] !== "admin")
{
  header('location: user.home.php');
}

//datum en tijd worden in een variabele gezet
require_once 'includes/date.php';

if(isset($_GET['id']) && ctype_digit($_GET['id']))
{
  $myId = $_GET['id'];
}
else
{
  header('location: logout.php');
}

//als er op morgen wordt gedrukt wordt de datum veranderd naar morgen
if(isset($_POST['morgen'])){
  $datumToday = $datumTomorrow;
  $datumTodayNL = $datumTomorrowNL;
}

//autonummer en begintijd worden opgehaald
$query = "SELECT autonummer, begintijd FROM ritten WHERE datum = '$datumToday' AND user_id = " . mysqli_escape_string($db, $myId);
$result2 = mysqli_query($db, $query);
$row1 = mysqli_fetch_assoc($result2);

//alle gegevens van de datum van vandaag en de gebruiker worden opgehaald
$queryAll = "SELECT * FROM ritten WHERE datum = '$datumToday' AND user_id = " . mysqli_escape_string($db, $myId);
$result = mysqli_query($db, $queryAll);

//gegevens worden in een array gezet
$ritten = [];
while($row = mysqli_fetch_assoc($result))
{
  $ritten[] = $row;
}

//alle gegevens van de geselecteerde gebruiker worden opgehaald
$sql = mysqli_query($db, "SELECT * FROM users WHERE user_id = " . mysqli_escape_string($db, $myId));
$row2 = mysqli_fetch_array($sql);

mysqli_close($db);
?>
<!DOCTYPE HTML>
<html>
<head>
  <title>Bekijken</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300italic' rel='stylesheet' type='text/css'>
</head>
<body>

<header>
  <nav>
    <ul>
      <li><a href="home.php">Home</a></li>
      <li><a href="create.php">Gebruiker aanmaken</a></li>

      <ul style="float:right;list-style-type:none;">
        <li><a>Hallo <?php echo $user_check; ?></a></li>
        <li><a href="personalinfo.php">Gegevens</a></li>
        <li><a href="logout.php">Uitloggen</a> </li>
      </ul>
    </ul>
  </nav>
</header>

<main>
  <div>
    <ul>
      <li>
        <form method="post" action="<?php $_SERVER['REQUEST_URI']; ?>">
          <input type="submit" name="vandaag" value="vandaag" />
        </form>
      </li>
      <li>
        <form method="post" action="<?php $_SERVER['REQUEST_URI']; ?>">
          <input type="submit" name="morgen" value="Morgen" />
        </form>
      </li>
    </ul>
  </div>
  <br/>
  <div class="headplanning">
    <h3>Dit is de planning van: <mark><?= $row2['username']; ?></mark> op <mark><?= $datumTodayNL; ?></mark>  </h3>
  </div>
<div class="planningform">
  <div class="usertable2">
    <table cellspacing="0">
      <thead>
      <tr>
          <th><?= $datumTodayNL; ?></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th>Tijd: <?= $row1['begintijd']; ?></th>
        </tr>
        <tr>
          <th>Opdrachtgever</th>
          <th>Omschrijving</th>
          <th>Laden/lossen</th>
          <th>Plaats</th>
          <th>Bijzonderheden</th>
          <th>Autonummer</th>
          <th>Geweest</th>
          <th>Terugkoppeling</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if($datumToday) {
          foreach ($ritten as $key => $value) { ?>
            <tr>
              <td><?php echo $value['opdrachtgever']; ?></td>
              <td><?php echo $value['omschrijving']; ?></td>
              <td><?php echo $value['ladenlossen']; ?></td>
              <td><?php echo $value['plaats']; ?></td>
              <td><?php echo $value['bijzonderheden']; ?></td>
              <td><?php echo $value['autonummer']; ?></td>
              <td><?php echo $value['geweest']; ?></td>
              <td><?php echo $value['tekstuser']; ?></td>
            </tr>
          <?php }
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
</main>

</body>
</html>
