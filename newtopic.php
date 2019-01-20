<?php
//Skript för att skapa en ny tråd i en kategori. Låter inte användaren skapa en tråd om denne inte är inloggad, på ett ganska fult sätt.
session_start();
if (!isset($_SESSION['id'])) {
    echo "<script type='text/javascript'>alert('Du är inte inloggad');window.history.back();</script>";
} else {
    if (isset($_GET['category'])) {
        ob_start();
        include_once('script/dbconnection.php');
        include('script/header.php');
        $header = ob_get_clean();
        ob_start();
        include('script/rightcolumn.php');
        $rightcolumn = ob_get_clean();
        ob_start();
        include('script/leftcolumn.php');
        $leftcolumn = ob_get_clean();
        $html = file_get_contents('html/newtopic.html');
        $html = str_replace('<!--catname-->', $_SESSION['curr_category_name'], $html);
        $parts = explode('<!--header-->', $html);
        $html = $parts[0] . $header . $parts[1] . $parts[2];
        $html = str_replace('<!--rightcolumn-->', $rightcolumn, $html);
        $html = str_replace('<!--leftcolumn-->', $leftcolumn, $html);
        echo $html;
        if(isset($_POST['file'])){
            //TODO ladda upp bild till inlägget?
        }
        if (isset($_POST['rubrik']) && isset($_POST['text']) && !empty(trim($_POST['rubrik'])) && !empty(trim($_POST['text']))) {
          //Om alla nödvändiga parameterar i POST-requesten från formuläret är ifyllda (trimmade) så skickas de till databasen, och användaren skickas tillbaka till kategorin där tråden skapades.
            if ($sql = $dbconnection->prepare('INSERT INTO topics (category, id, title, author, created, text) VALUES (?, DEFAULT, ?, ?, DEFAULT, ?)')) {
                $cat = $_GET['category'];
                $text = htmlspecialchars(trim($_POST['text']));
                $title = htmlspecialchars(trim($_POST['rubrik']));
                $author = trim($_SESSION['id']);
                $sql->bind_param('isis', $cat, $title, $author, $text);
                $sql->execute();
                header('Location: forums.php?category=' . $cat);
            } else {
                die($dbconnection->error);
            }
        }
    } else {
        die("Ett fel inträffade");
    }
}
?>
