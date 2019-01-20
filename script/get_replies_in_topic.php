<?php
/*
Skript för att visa innehållet i en tråd. Hämtar alla svar som härrör med en specifik topic (som identifieras med dess ID). topic id agerar främmande nyckel i svar-tabellen.
*/
include_once('dbconnection.php');
include_once('get_author_by_id.php');
function get_replies($topicid){
    global $dbconnection;
    $replies = array();
    $repliesquery = $dbconnection->query('SELECT posts.id, posts.author, posts.content, posts.created FROM posts WHERE topic='.$topicid);
    if($repliesquery){
        while ( $row = $repliesquery->fetch_assoc() ){
            $row['author'] = get_author($row['author']);
            $replies[] = $row;
        }
    }
    return $replies;
}
