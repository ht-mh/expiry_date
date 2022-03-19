<?php
session_start();
require_once('../library.php');
require_once('../functions.php');
$_SESSION['id'] = '';

$username = $_SESSION['username'];
$id = account_id($username);



?>