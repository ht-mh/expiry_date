<?php
//htmlspecialcharasを短くする
function h($value){
    return htmlspecialchars($value, ENT_QUOTES);
}


//DBへの接続
function dbconnect(){
    $db = new mysqli('localhost','root','root', 'expiry_date');
    if(!$db){
        die($db ->error);
    }
    return $db;
}

//sessionの情報の受け渡し
function session_array($session_id_value,$session_username_value){
    $id =$session_id_value;
    $username = $session_username_value;
    return [$id,$username];
}

?>