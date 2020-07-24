<?php
// this document is for new users signing up to use the service

require_once "../private/Authentication.php";
require_once "../private/Database.php";
session_start(); 

$auth = new Authentication();

$database = new Database();
$db = $database->getDatabase();

?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" type="text/css" href="../public/css/login-form.css">  
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
</head>
<body>
<?php
 
if(!array_key_exists("sidVerified", $_SESSION))
{
    $_SESSION["sidVerified"] = false; 
}

$auth->printSignUpForm(); 

?>
</body>
</html>

