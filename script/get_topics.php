<?php
/*
Hämtar alla trådar i kategorin (som hämtas ut ur $_SESSION-arrayen). Detta värde sätts när man klickar in på en kategori, i forums.php-skriptet. Ser nu att jag, i onödan, skapar en till
variabel i $_SESSION i onödan ('curr_category_id') som har samma uppgift som den redan existerande 'category'. För sent för att fixa nu dock..
När svar från första queryn kommer, körs en ny query för att hämta ut namnet på skribenten (som visas på sidan). Resultatet från dessa querys sparas sedan i en associative array
som används av JS-skriptet.
*/
  if(!isset($_SESSION))
  {
    session_start();
  }
  include_once('dbconnection.php');
  include_once('get_author_by_id.php');
  $result = array();
  $categoryid = $_SESSION["category"];
  $query = 'SELECT categories.name, topics.title, topics.author, topics.created, topics.id FROM topics INNER JOIN categories ON topics.category = categories.id AND categories.id = ? ORDER BY topics.created';
  if ( $sql = $dbconnection->prepare($query) ) {
    $sql->bind_param('i', $categoryid);
    $sql->execute();
    $sql->store_result();
    $sql->bind_result($catname,$title, $author, $created, $id);
    while ( $sql->fetch() ){
      $author = get_author($author);
      $postcountquery = $dbconnection->query('SELECT COUNT(*) as amt FROM posts WHERE topic='.$id);
      $count = $postcountquery->fetch_assoc()['amt'];
      $row = array("category"=>$categoryid, "title"=>$title, "author"=>$author, "created"=>$created, "id"=>$id, "catname"=>$catname, "count"=>$count);
      $result[] = $row;
    }
    $_SESSION['curr_category_id'] = $categoryid;
    $_SESSION['curr_category_name'] = $catname;
    echo json_encode($result);
  } else {
    echo $dbconnection->error;
  }
