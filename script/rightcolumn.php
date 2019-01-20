<?php
//skript som används av de andra huvudskripten för att inkludera högerkolumnen.
include('dbconnection.php');
$path = $_SERVER['DOCUMENT_ROOT'];
$html = file_get_contents($path.'/gesall/html/rightcolumn.html');
echo $html;
