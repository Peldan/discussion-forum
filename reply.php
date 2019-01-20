<?php
session_start();
if ( !isset($_SESSION['id']) ){
    echo "<script type='text/javascript'>alert('Du är inte inloggad');window.history.back();</script>"; //Tillåter inte användaren att svara i trådar om denne inte är inloggad
    //(på ett ganska fult sätt).
} else {
    if( isset($_POST['submitbtn']) && isset($_POST['text']) && !empty(trim($_POST['text'])) ) {
        include_once('script/dbconnection.php');
        $text = htmlspecialchars(trim($_POST['text'])); //för att undvika XSS/annan html i textfälten. HTML-relaterade tecken förblir endast tecken och tolkas inte av webbläsaren.
        if( $sql = $dbconnection->prepare('INSERT INTO posts (id, topic, author, content, created) VALUES (DEFAULT, ?, ?, ?, DEFAULT)') ) {
            $topic = $_SESSION['curr_topic_id'];
            $author = $_SESSION['id'];
            $sql->bind_param('iis',$topic,$author, $text);
            $sql->execute();
            header('Location: forums.php?category='.$_SESSION['curr_category_id'].'&topic='.$topic); //efter ett lyckat svar skickas man tillbaka till den aktuella tråden.
        } else {
            die($dbconnection->error);
        }
    } else {
        include('script/header.php');
        $header = ob_get_clean();
        $html = file_get_contents('html/reply.html');
        $html = str_replace('<!--topicname-->', $_SESSION['curr_topic_name'], $html);
        $html = str_replace('<!--catname-->', $_SESSION['curr_category_name'], $html);
        $parts = explode('<!--header-->', $html);
        $html = $parts[0].$header.$parts[1].$parts[2];
        echo $html;
    }
}
