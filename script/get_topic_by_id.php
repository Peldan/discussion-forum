<?php
include('dbconnection.php');
function get_topic($topicid){
    global $dbconnection;
    $topicquery = $dbconnection->query('SELECT title FROM topics WHERE id = ' . $topicid);
    if($topicquery){
        $topicname = $topicquery->fetch_assoc()['title'];
        return $topicname;
    }
}
