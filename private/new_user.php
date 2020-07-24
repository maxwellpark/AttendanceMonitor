<?php
require_once "Authentication.php";
require_once "Database.php";

session_start();
$_SESSION['sidVerified'] = false; 

$auth = new Authentication(); 

$database = new Database();
$db = $database::getDatabase(); 

$sid = $_POST["sid"];

$auth->verifySID($db, $sid);