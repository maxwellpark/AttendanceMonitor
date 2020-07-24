<?php
require_once "Authentication.php";
require_once "Database.php";
session_start();

$auth = new Authentication();

$database = new Database();
$db = $database::getDatabase(); 

$auth->handleLogin($db, $_POST['username'], $_POST['password']); 