<?php
require_once('library.php');

function account_exist($username){
    $db = dbconnect();
    $sql = 'SELECT count(*) FROM members WHERE username=?';        
    $stmt = $db->prepare($sql);
    if(!$stmt){
        die($db->error);
    }
    $stmt->bind_param('s',$username);
    $success = $stmt->execute();
    if(!$success){
        die($db->error);
    }

    $stmt->bind_result($cnt);
    $db = null;
    if($cnt > 0){
        $result = 'true';
    }{
        $result = 'false';
    }
    return $result;
}


function account_regist($username,$password){
    $db = dbconnect();
    $sql = 'insert into members (username, password) VALUES(?,?)';
    $stmt = $db->prepare($sql);
	if(!$stmt){
		die($db->error);
	}
	$stmt->bind_param('ss',$username, $password);
	$success = $stmt->execute();
	if(!$success) {
		die($db->error);
	}
    $db = null;
    return $success;
}

function account_id($username){
    $db = dbconnect();
    $sql = 'select id from members where username=?';
    $stmt = $db->prepare($sql);
    if(!$stmt){
		die($db->error);
	}
    $stmt->bind_param('s',$username);
    $stmt->execute();
    $stmt->bind_result($id);
    $db = null;
    return $id;
}

function login_check($username,$password){
    $db = dbconnect();
    $sql = 'select password from members where username=?';
    $stmt = $db->prepare($sql);
    if(!$stmt){
		die($db->error);
	}
    $stmt->bind_param('s',$username);
    $stmt->execute();
    $stmt->bind_result($hash);
    if(is_null($username)){
        $result = 'a';
    }elseif(password_verify($password, $hash)){
        $result = 'success';
    }else{
        $result = 'b';
    }

    return $result;
}

?>