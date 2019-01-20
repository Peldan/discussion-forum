<?php
//Tidigare använd för att visa antalet registrerade användare i vänsterkolumnen.
include_once('dbconnection.php');
$response = array();
$usercountquery = $dbconnection->query('SELECT COUNT(*) as users FROM users');
if ( $usercountquery ) {
    $count = $usercountquery->fetch_assoc()['users'];
    $response['users'] = $count;
}

echo json_encode($response);
