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

//alle gegevens van de gebruikers worden opgehaald
//wordt gebruikt om uit een lijst met gebruikers te kiezen
$query = mysqli_query($db, "SELECT * FROM users WHERE user_role = 'user'");

//alle gegevens van gebruikers worden opgehaald
$queryUser = "SELECT * FROM users WHERE user_role = 'user'";
$userlist = mysqli_query($db, $queryUser);

//gegevens worden in een array gezet
$users = [];
while($row = mysqli_fetch_assoc($userlist)) {
  $users[] = $row;
}

//als er op maak aan wordt gedrukt
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

//er wordt geloopt door alle inputs
    foreach ($opdrachtgever as $key => $value) {
      //alle input worden in de database gezet door middel van een array
      //dit wordt in de formulier ook gedaan door middel van []
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
echo 'Planning aangemaakt';
  }
  else
  {
    echo 'Datum + tijd kunnen niet leeg blijven';
  }
}
mysqli_close($db);
?>
<!DOCTYPE HTML>
<html>
<head>
  <title>Het Groene Hart Koeriers</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300italic' rel='stylesheet' type='text/css'>
</head>
<body>


<main>
  <div class="headplanning">
    <h3>Maak een planning aan:</h3>
  </div>
  <div class="planningform">
  <form name="formplanning" action="<?php $_SERVER['REQUEST_URI']; ?>" method="post" >

    <table id="dataTable">
      <td><input type="checkbox" name="checked[]" /></td>
    <td><input type="text" name="opdrachtgever[]" placeholder="opdrachtgever" value="<?php if(isset($_POST['submit'])){} ?>" size="15" maxlength="50" /></td>
    <td><input type="text" name="omschrijving[]" placeholder="omschrijving" size="39px" maxlength="200"/></td>
    <td><select class="select" name="ladenlossen[]">
        <option value="laden">laden</option>
        <option value="lossen">lossen</option>
        <option value="laden + lossen">laden+lossen</option>
      </select></td>
    <td><input type="text" name="plaats[]" placeholder="plaats" size="20" maxlength="50"/></td>
    <td><input type="text" name="bijzonderheden[]" placeholder="bijzonderheden" size="35" maxlength="200" /></td>
      <td><input class="autonummer" type="text" name="autonummer[]" maxlength="2" size="3" placeholder="auto" /></td>

      <input class="addbutton" type="button" value="+" onClick="addRow('dataTable')" />
    </table>
    <input class="deletebutton" type="button" value="Verwijder rit" onClick="deleteRow('dataTable')" />
    <div class="send">

      <label>Chauffeur: </label>
        <select class="select" name="chauffeur">
          <?php while ($rowtwo = mysqli_fetch_array($query))
          {
            echo "<option value='".$rowtwo['user_id']."'>".$rowtwo['username']."</option>";
          }
          ?>
        </select>
      <label>Datum: </label>
      <input class="datum" type="date" id="date" name="datum" />
      <label>Begintijd: </label>
      <input class="beginTijd" name="beginTijd" type="text" maxlength="7" size="7" />
      <input type="submit" name="submit" value="Maak aan" />
    </div>

  </form>
  </div>
  <div class="userbeheren">
    <h3>Planningen beheren</h3>
    <div class="userbeheren2">
      <table>
        <tr>
          <?php foreach($userlist as $key => $value){ ?>
          <td><?php echo $value['username']; ?></td>
          <td><a href="edit.php?id=<?php echo $value['user_id']; ?>">Wijzig planning</a></td>
        </tr>
        <?php } ?>
      </table>

    </div>
  </div>


  <div class="userbeheren">
    <h3>Planningen bekijken</h3>
    <div class="userbeheren2">
      <table>
        <tr>
          <?php foreach($userlist as $key => $value){ ?>
          <td><?php echo $value['username']; ?></td>
          <td><a href="watch.php?id=<?php echo $value['user_id']; ?>">Bekijk planning</a></td>
        </tr>
        <?php } ?>
      </table>

    </div>
  </div>

</main>

<script src="javascript.js" type="text/javascript"></script>
</body>
</html>
