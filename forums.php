<?php
/*
"Huvudskript" för forum-vyn. Visar både kategorivyn och trådvyn. Innehållet bestäms av $_GET-värden, dvs om "topic" inte är satt så är man endast inne och kollar innehållet i en
kategori, och har ännu inte klickat på en tråd. Detta skript, likt de andra "huvudskripten", använder högerkolumnen, headern och vänsterkolumnen genom att hämta dem
från deras respektive skript och sparar dem i variabler genom output buffern.
*/
if (!isset($_SESSION[''])) session_start();
ob_start();
include('script/header.php');
$header = ob_get_clean();
ob_start();
include('script/rightcolumn.php');
$rightcolumn = ob_get_clean();
ob_start();
include('script/leftcolumn.php');
$leftcolumn = ob_get_clean();
if (isset($_GET['category']) && !empty(trim($_GET['category']))) {
    if (isset($_GET['topic']) && !empty(trim($_GET['topic']))) {
        $topic = $_GET['topic'];
        $_SESSION['curr_topic_id'] = $topic;
        $html = file_get_contents('html/topic.html');
        $html = str_replace('<!--catid-->', $_SESSION['curr_category_id'], $html);
        $html = str_replace('<!--catname-->', $_SESSION['curr_category_name'], $html);
        $html = str_replace('<!--topicid-->', $_SESSION['curr_topic_id'], $html);
        $html = str_replace('<!--topicname-->', "Denna tråd", $html); //TODO visa topicnamnet i navbar..
    } else {
        $html = file_get_contents('html/forums.html');
    }
    $parts = explode('<!--header-->', $html);
    $html = $parts[0] . $header . $parts[1] . $parts[2];
    $html = str_replace('<!--rightcolumn-->', $rightcolumn, $html);
    $html = str_replace('<!--leftcolumn-->', $leftcolumn, $html);
    $id = $_GET['category'];
    $_SESSION['category'] = $id;
    $html = str_replace('<!--catid-->', $id, $html);
}
echo $html;
