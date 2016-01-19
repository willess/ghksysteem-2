<?php

//datum wordt omgezet naar nederlandse notatie
setlocale(LC_ALL, 'nl_NL');

//datum nederlandse notatie wordt in een variable gezet
$datumTodayNL = strftime("%A %e %B %Y");
$datumTomorrowNL = strftime("%A %e %B %Y", strtotime('+1 day'));

//amerikaanse tijdnotatie wordt in een variabele gezet
$datumToday = date("Y-m-d");
$datumTomorrow = date("Y-m-d", strtotime('+1 day'));

$time = date("H:i");

?>
