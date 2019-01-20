<?php
/*
Skript som används av de andra huvudskripten för att visa headern (logon och konto-info)
*/
$path = $_SERVER['DOCUMENT_ROOT'];
$html = file_get_contents($path.'/gesall/html/header.html');
if ( isset($_SESSION['user']) && isset($_SESSION['id']) ){
  $html = str_replace('<!--logintext-->', '<td><a href="profile.php?id='.$_SESSION['id'].'" id="loggain" class="konto">'.$_SESSION['user'].'</a></td>', $html);
  $html = str_replace('<!--registertext-->', '<td><a href="signout.php" id="registrera" class="konto">Logga ut</a></td>', $html);
} else {
  $html = str_replace('<!--logintext-->', '<td><a href="login.php?register=false" id="loggain" class="konto">Logga in</a></td>', $html);
  $html = str_replace('<!--registertext-->', '<td><a href="login.php?register=true" id="registrera" class="konto">Registrera</a></td>', $html);
}
echo $html;
