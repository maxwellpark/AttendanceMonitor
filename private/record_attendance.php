<?php
require_once "Database.php"; 

$database = new Database();
$db = $database::getDatabase(); 

$sid = $_POST['sid']; 
$week = "WEEK_" . $_POST['week'];
$module = "MOD" . $_POST['module'];
$_POST["attended"] == "Attended" ? $attended = 1 : $attended = 0; 

// inserts values from the dropdown into the notification table 
$query = $db->prepare("UPDATE attendance SET $week=? WHERE sid =? AND MOD_ID =?"); 
$query->execute([$attended, $sid, $module]);

if($query->rowCount() > 0) 
{
    $message = "Attendance records updated"; 
}


$query = $db->prepare("SELECT * FROM notifications WHERE sid=? AND week=? AND module=?"); 
$query->execute([$sid, $_POST['week'], $_POST['module']]);
if($query->rowCount() > 0)
{
    // delete notification entry as attendance is now recorded 
    $query = $db->prepare("DELETE FROM notifications WHERE sid=? AND week=? AND module=?"); 
    $query->execute([$sid, $_POST['week'], $_POST['module']]);
}

header("Location: " . $_SERVER["HTTP_REFERER"] . "?=$message"); 