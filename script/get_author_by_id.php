<?php
include('dbconnection.php');
/*
Hjälpskript för att hämta ut användarnamnet som är associerat med ett visst ID (som skickas med som parameter) från databasen. Deklarerar $dbconnection som global för att kunna komma
åt det globala värdet (som kommer från skriptet som includas)
*/
function get_author($authorid){
    global $dbconnection;
    $authorquery = $dbconnection->query('SELECT username FROM users WHERE id = ' . $authorid);
    if($authorquery){
        $author = $authorquery->fetch_assoc()['username'];
        return $author;
    }
}
