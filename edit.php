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
  header('location: edit.php');
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

//alle ritten worden opgehaald met de datum van vandaag en de gebruiker die is aangeklikt
$queryAll = "SELECT * FROM ritten WHERE datum = '$datumToday' AND user_id = " . mysqli_escape_string($db, $myId);
$result = mysqli_query($db, $queryAll);

//ritten worden in een array gezet
$ritten = [];
while($row = mysqli_fetch_assoc($result))
{
  $ritten[] = $row;
}


//alle gegevens van de gebruiker worden opgehaald
$sql = mysqli_query($db, "SELECT * FROM users WHERE user_id = " . mysqli_escape_string($db, $myId));
$row = mysqli_fetch_array($sql);

//alle gegevens van de gebruikers worden opgehaald
//deze query wordt in de tabel gebruik in een while loop
$query = mysqli_query($db, "SELECT * FROM users WHERE user_role = 'user'");

//als er op wijzigen is geklikt
if(isset($_POST['submit'])) {

  //er wordt gecheckt of de datum en tijd zijn ingevuld
  $ok = true;

  if (!isset($_POST['datum']) || $_POST['datum'] === '') {
    $ok = false;
  }
  if (!isset($_POST['beginTijd']) || $_POST['beginTijd'] === '') {
    $ok = false;
  }

  if ($ok) {
    //inputs worden in variabelen gezet
    $opdrachtgever = $_POST['opdrachtgever'];
    $omschrijving = $_POST['omschrijving'];
    $ladenlossen = $_POST['ladenlossen'];
    $plaats = $_POST['plaats'];
    $bijzonderheden = $_POST['bijzonderheden'];
    $chauffeurId = $_POST['chauffeur'];
    $datum = $_POST['datum'];
    $autonummer = $_POST['autonummer'];
    $begintijd = $_POST['beginTijd'];

//alle gegevens worden verwijderd met de datum van vandaag en de gebruiker
    $sql = "DELETE FROM ritten WHERE datum = '$datumToday' AND user_id = $myId ";
    mysqli_query($db, $sql);

    //er wordt geloopt door alle inputs van de formulier die is aangemaakt
    foreach ($opdrachtgever as $key => $value) {
      $sql = sprintf("INSERT INTO ritten(user_id, opdrachtgever, omschrijving, ladenlossen, plaats, bijzonderheden, autonummer, begintijd, datum)
                          VALUES ('$chauffeurId', '$opdrachtgever[$key]', '$omschrijving[$key]', '$ladenlossen[$key]', '$plaats[$key]', '$bijzonderheden[$key]', '$autonummer[$key]', '$begintijd', '$datum')
                          ",
          mysqli_real_escape_string($db, $chauffeurId),
          mysqli_real_escape_string($db, $opdrachtgever[$key]),
          mysqli_real_escape_string($db, $omschrijving[$key]),
          mysqli_real_escape_string($db, $ladenlossen[$key]),
          mysqli_real_escape_string($db, $plaats[$key]),
          mysqli_real_escape_string($db, $bijzonderheden[$key]),
          mysqli_real_escape_string($db, $autonummer[$key]),
          mysqli_real_escape_string($db, $begintijd),
          mysqli_real_escape_string($db, $datum));

          mysqli_query($db, $sql);
    }
      echo 'Planning gewijzigd';
  }
  else
  {
    echo 'Datum + tijd kunnen niet leeg blijven';
  }
}

//de rit wordt verwijderd als deze is gecheckt
if(isset($_POST['delete']))
{
  $checked = $_POST['checked'];

  foreach($checked as $key => $value)
  {
  mysqli_query($db, "DELETE FROM ritten WHERE datum = '$datumToday' AND id = '$value' AND user_id = " . mysqli_escape_string($db, $myId));
}
  header('Location: '.$_SERVER['REQUEST_URI']);
}

mysqli_close($db);
?>
<!DOCTYPE HTML>
<html>
<head>
  <title>Wijzigen</title>
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
  <a href="edit.php?id=<?php echo $row['user_id']; ?>">Vandaag</a>
  <a href="edit2.php?id=<?php echo $row['user_id']; ?>">Morgen</a>



  <div class="headplanning">
    <h3>Wijzig de planning van: <mark><?= $row['username']; ?></mark> op <mark><?= $datumTodayNL; ?></mark> </h3>
  </div>
  <div class="planningform">
    <form name="formplanning" action="<?php $_SERVER['REQUEST_URI']; ?>" method="post" >
      <?php foreach ($ritten as $key => $value) { ?>
      <table id="dataTable">
        <td><input type="checkbox" name="checked[]" value="<?=$value['id']?>"/></td>
        <td><input type="text" name="opdrachtgever[]" value="<?= $value['opdrachtgever']; ?>" size="15" /></td>
        <td><input type="text" name="omschrijving[]" value="<?= $value['omschrijving']; ?>" size="45px"/></td>
        <td><select class="select" name="ladenlossen[]">
            <option value="<?= $value['ladenlossen']; ?>"><?= $value['ladenlossen']; ?></option>
            <option value="laden">laden</option>
            <option value="lossen">lossen</option>
            <option value="laden + lossen">laden+lossen</option>
          </select></td>
        <td><input type="text" name="plaats[]" value="<?= $value['plaats']; ?>" size="20" /></td>
        <td><input type="text" name="bijzonderheden[]" value="<?= $value['bijzonderheden']; ?>" size="35" /></td>
        <td><input class="autonummer" type="text" name="autonummer[]" value="<?= $value['autonummer']; ?>" maxlength="2" size="2" /></td>
      </table>

      <?php } ?>


      <input class="deletebutton" type="submit" name="delete" value="Verwijder rit" />


      <div class="send">
        <label>Chauffeur: </label>
        <select class="select" name="chauffeur">
          <?php echo "<option value='".$row['user_id']."'>".$row['username']."</option>"; ?>
          <?php while ($rowtwo = mysqli_fetch_array($query))
          {
            echo "<option value='".$rowtwo['user_id']."'>".$rowtwo['username']."</option>";
          }
          ?>
        </select>
        <label>Datum: </label>

        <input class="datum" type="date" id="date" name="datum" value="<?= $value['datum']; ?>" />
        <label>Begintijd: </label>
        <input class="beginTijd" type="text" name="beginTijd"  value="<?= isset($value['begintijd']) ? $value['begintijd'] : ''; ?>" maxlength="7" size="7" />
        <input type="submit" name="submit" value="Wijzig" />
      </div>
    </form>
  </div>
</main>

</body>
</html>
