<?php
/*
Hjälpskript för att hämta ut kategorier som ska visas på index.php-sidan. Gör en left join för att kunna räkna antalet topics som finns i kategorin, som också presenteras på index.php
Resultatet jsonifieras sen och echoas ut som svar till JS-skriptet som anropat det.
*/
  require_once('dbconnection.php');
  if ( $sql = $dbconnection->query('SELECT cat.id, name, COUNT(topics.category) as topic_amount
    FROM(SELECT name, id FROM categories) AS cat LEFT JOIN topics ON cat.id = topics.category GROUP BY topics.category DESC') ){
    $rows = array();
    while ( $row = $sql->fetch_assoc() ){
      $rows[] = $row;
    }
    echo json_encode($rows);
  } else {
    echo $dbconnection->error;
  }
?>
