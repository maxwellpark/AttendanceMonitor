<?php
require_once "Routing.php";

$routing = new Routing();
$routing->logoutUser(); 

// // reset session for the user logging out 
// if(isset($_SESSION))
// {
//     session_start();
//     session_unset();
//     session_destroy();
//     session_write_close();
//     setcookie(session_name(),'',0,'/');
//     session_regenerate_id(true);
// }
// // redirect to login page 
// header("Location: /AttendanceMonitor/public/" /* include response code param? */); 

