<?php
/*
Skript för att hämta ut senaste aktiviteten på forumet, denna information ska sedan visas i högerkolumnen på sidan. En array vardera skapas för trådar och svar. Dessa
populeras sedan som resultat av den SQL som körs. Båda sorteras såklart efter datumet de skapades, med en gräns på 3. 
*/
include_once('dbconnection.php');
include_once('get_author_by_id.php');
include_once('get_topic_by_id.php');
include_once('get_category_for_topic.php');
$latesttopics = array();
$latestposts = array();
$topicquery = $dbconnection->query('SELECT topics.category, topics.id, topics.title, topics.author, topics.created FROM topics ORDER BY topics.created DESC LIMIT 3');
$postquery = $dbconnection->query('SELECT posts.id, posts.topic, posts.author, posts.created FROM posts ORDER BY posts.created DESC limit 3');
$response = array();
if ( $topicquery ) {
    while ( $row = $topicquery->fetch_assoc() ){
        $row['author'] = get_author($row['author']);
        $latesttopics[] = $row;
    }
}
if ( $postquery ) {
    while ( $row = $postquery->fetch_assoc() ){
        $row['author'] = get_author($row['author']);
        $row['category'] = get_category($row['topic']);
        $row['topicname'] = get_topic($row['topic']);
        $latestposts[] = $row;
    }
}
$response['topics'] = $latesttopics;
$response['posts'] = $latestposts;
echo json_encode($response);
