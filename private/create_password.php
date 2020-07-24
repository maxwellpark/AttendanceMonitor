<?php
require_once "Authentication.php"; 
require_once "Database.php"; 
session_start(); 

$auth = new Authentication();

$database = new Database();
$db = $database::getDatabase(); 

$auth->createAndStorePassword($db, $_SESSION["sid"], $_POST['password']); 
