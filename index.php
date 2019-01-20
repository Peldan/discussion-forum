<?php
//Det första användaren möts av. Visar samtliga kategorier som finns på forumet, tillsammans med vänster & höger-kolumn samt header (som de andra sidorna också har). Kategorierna hämtas
//med hjälp av JavaScript som skickar request till PHP-skriptet get_categories.php.
session_start();
ob_start();
include('script/header.php');
$header = ob_get_clean();
ob_start();
include('script/rightcolumn.php');
$rightcolumn = ob_get_clean();
ob_start();
include('script/leftcolumn.php');
$leftcolumn = ob_get_clean();
$html = file_get_contents('html/index.html');
$parts = explode('<!--header-->', $html);
$html = $parts[0] . $header . $parts[1] . $parts[2];
$html = str_replace('<!--rightcolumn-->', $rightcolumn, $html);
$html = str_replace('<!--leftcolumn-->', $leftcolumn, $html);
echo $html;
