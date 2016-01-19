<?php
session_start();
//wanneer er op uitloggen wordt geklikt wordt de sessie gestopt
//en wordt je naar de inlogpagina te sturen
if(session_destroy())
{
    header('location: index.php');
}
?>
