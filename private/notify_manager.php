<?php
require_once "Student.php";
require_once "Database.php";
session_start();

$student = new Student();

$database = new Database();
$db = $database::getDatabase();

$query = $db->prepare("INSERT INTO notifications VALUES (:sid, :week, :module, :info)");
$query->execute(array
(
    ":sid" => $_SESSION['sid'],
    ":week"=> $_POST['week'], 
    ":module" => $_POST['module'],
    ":info" => $_POST['info']
));

header("Location: " . $_SERVER["HTTP_REFERER"]); 
