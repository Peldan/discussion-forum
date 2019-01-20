<?php
//samma princip som get_author_by_id..
include('dbconnection.php');
function get_category($topicid){
    global $dbconnection;
    $categoryquery = $dbconnection->query('SELECT id FROM categories WHERE categories.id IN (SELECT category FROM topics WHERE topics.id = '.$topicid.')');
    if($categoryquery){
        $category = $categoryquery->fetch_assoc()['id'];
        return $category;
    }
}
