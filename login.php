<?php
/*
Inloggnings- och registreringsskript i ett och samma skript som baserar dess beteende på GET-värden (register=true/false).
Ändrar dels sidans innehåll baserat på detta, men även såklart databas-kommunikationen. När man registrerar sig måste en ny användare INSERTas i databasen,
och när man loggar in behöver man endast hämta uppgifter om användaren, exempelvis deras id, som sedan sparas i $_SESSION-arrayen för att veta att användaren är inloggad för tillfället.
Detta värde används till exempel i headern för att visa användarnamnet och möjligheten att logga ut. Vid både inloggning och registrering skickas man tillbaka till index.php med hjälp av
header-ändring. Lösenorden är lagrade i plain text vilket knappast är optimalt, men det var inte riktigt i fokus för uppgiften. 
*/
session_start();
require_once('script/dbconnection.php');
$html = file_get_contents('html/login.html');
$register;
if (isset ($_GET['register']) && $_GET['register'] == 'false') {
    $register = false;
    $html = str_replace('<!--action-->', 'Logga in', $html);
    $html = str_replace('<!--postaction-->', 'login.php?register=false', $html);
}
else if (isset($_GET['register']) && $_GET['register'] == 'true') {
    $register = true;
    $html = str_replace('<!--action-->', 'Registrera', $html);
    $html = str_replace('<!--postaction-->', 'login.php?register=true', $html);
}
echo $html;
if ( isset($_POST["submit"]) && isset($_POST["username"]) && isset($_POST["password"]) && !(empty(trim($_POST["username"]))) && !empty(trim(($_POST["password"]))) ) {
    $username = trim($_POST["username"]);
    $enteredpassword = trim($_POST["password"]);
    if ( isset($_GET['register']) && $register == false && $sql = $dbconnection->prepare('SELECT id, password FROM users WHERE username = ?')){
        $sql->bind_param('s', $username);
        $sql->execute();
        $sql->store_result();
        $sql->bind_result($id, $password);
        if($result = $sql->fetch()){
            if($enteredpassword === $password){
                $_SESSION['id'] = $id;
                $_SESSION['user'] = $username;
                header('Location: index.php');
            } else {
                echo '<h1>Fel användarnamn eller lösenord.</h1>';
            }
        } else {
            echo '<h1>Fel användarnamn eller lösenord.</h1>';
        }
    }
    else if ( isset($_GET['register']) && $register == true && $sql = $dbconnection->prepare('INSERT INTO users (id, username, password, created) VALUES(DEFAULT, ?, ?, DEFAULT)')){
        $sql->bind_param('ss', $username, $enteredpassword);
        if( $sql->execute() ){
            if ( $idquery = $dbconnection->prepare('SELECT id FROM users WHERE username = ?') ) {
                $idquery->bind_param("s", $username);
                $idquery->execute();
                $idquery->store_result();
                $idquery->bind_result($id);
                if( $result = $idquery->fetch() ){
                    $_SESSION['id'] = $id;
                    $_SESSION['user'] = $username;
                    header('Location: index.php');
                }
            }
        } else {
            echo '<h1>Ett fel har inträffat.</h1>';
        }
    }
    else {
        die('Fel: ' . $dbconnection->error);
    }
    $sql->close();
}
