<?php
  $dbconnection = new mysqli("localhost", "root", "", "forum");
  if( $dbconnection->connect_error ) die("Fel vid anslutning till databasen");
?>
