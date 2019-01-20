<?php
//Startar en ny session och förstör den sedan, för att nollställa exempelvis $_SESSION['id'] och andra användarkontorelaterade saker. Skickas sedan tillbaka till index.php-sidan. 
session_start();
session_unset();
session_destroy();
header('Location: index.php');
exit();
?>
