<?php
/*
Skript för att visa en tråd med dess original-post (dvs allt som "skaparen" av tråden gjorde).
*/
  if(!isset($_SESSION))
  {
    session_start();
  }
  include_once('dbconnection.php');
  include_once('get_author_by_id.php');
  include_once('get_replies_in_topic.php');
  $result = array();
  $categoryid = $_SESSION['category'];
  $topicid = $_SESSION['curr_topic_id'];
  $query = 'SELECT topics.id, topics.title, topics.author, topics.created, topics.text FROM topics WHERE topics.category = ? AND topics.id = ?';
  if ( $sql = $dbconnection->prepare($query) ) {
    $sql->bind_param('ii', $categoryid, $topicid);
    $sql->execute();
    $sql->store_result();
    $sql->bind_result($id,$title, $author, $created, $text);
    if( $sql->fetch() ){
      $_SESSION['curr_topic_name'] = $title;
      $author = get_author($author);
      $replies = get_replies($id);
      $row = array("title"=>$title, "author"=>$author, "created"=>$created, "text"=>$text, "replies"=>$replies);
      $result[] = $row;
    }
    echo json_encode($result);
  } else {
    echo $dbconnection->error;
  }
?>
