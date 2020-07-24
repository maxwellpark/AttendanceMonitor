<?php
require_once "../private/Authentication.php";

// confirm session cleanup 
session_start();
session_reset(); 
session_unset();
$_SESSION = array();
session_destroy();
session_write_close();

$auth = new Authentication(); 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendance Tracking System</title>
    <link rel="stylesheet" type="text/css" href="../public/css/login-form.css">  
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">

</head>
<body>
    <?php 
    // prints relevant messages to user 
    if(array_key_exists('error_message', $_GET)) 
    {
        echo "<p id='failed-login' class='incorrect-field'> {$_GET['error_message']} </p>";
    } 
    if(array_key_exists('success_message', $_GET))
    {
        echo "<div class='success_message-container'>
        <h3 class='success'>{$_GET['success_message']}</h3>
        </div>";
    }
    ?>

    <div class="login-form">
        <!-- staff are considered returning users --> 
        <h3 class="login-header">Sign In</h3>
        <form action="/AttendanceMonitor/private/login.php" method="post" autocomplete="off">
            <div class="login-textbox">
                <i class="fa fa-user" aria-hidden="true"></i>
                <label>Username</label>
				<input type="text" name="username" placeholder="Username" id="username" required>

                <i class="fa fa-key" aria-hidden="true"></i>
                <label>Password</label>
                
				<input type="password" name="password" placeholder="Password" id="password" required>

           <button type="submit" class="login-button">
               Submit
           </button>
        </form>
    <!-- this takes new students to the signup page -->
    <form action="signup.php"> 
    <button id="new-user-btn" type="submit" class="login-button">
        New Users Click Here
    </button> 
    </form> 
    </div>
    </div>     
</body>
</html>