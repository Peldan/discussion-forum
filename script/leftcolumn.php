<?php
//Skript som används av de andra huvudskripten för att inkludera vänsterkolumnen.
include('dbconnection.php');
$path = $_SERVER['DOCUMENT_ROOT'];
$html = file_get_contents($path.'/gesall/html/leftcolumn.html');
echo $html;
