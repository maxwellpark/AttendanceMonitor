<?php
//connect to database 
$dsn = 'mysql:host=127.0.0.1;dbname=attendance_monitor';
$user = 'root';
$password = '';
try 
{
    $db = new PDO($dsn, $user, $password);
} 
catch (PDOException $e) 
{
    die('Connection failed.');
}

$plaintext_passwords = array("orchidsunrise", "whiskey123", "meanqueen", "averagejoe");
$result = $db->query("SELECT username FROM staff");
$i = 0;

// loops through the staff, hashes the plaintext passwords, inserts them into db 
while($row = $result->fetch(PDO::FETCH_ASSOC)) 
{
    $update = $db->prepare("UPDATE staff SET password = :hash WHERE username = :username");
    $hash = md5($plaintext_passwords[$i]);
    $update->bindParam(":hash", $hash);
    $update->bindParam(":username", $row['username']); 
    $update->execute(); 
    $i++; 
}
