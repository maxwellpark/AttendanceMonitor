<?php

class Routing 
{
    public function redirectUnauthorisedUser($currentPrivilege, $requiredPrivilege) 
    {
        if($currentPrivilege !== $requiredPrivilege) 
        {
            // send unauthorised user back to the previous page 
            header("Location: " . $_SERVER["HTTP_REFERER"]); 
        }
    }

    public function logoutUser() 
    {
        // cleanup session for the user logging out 
        if(isset($_SESSION))
        {
            session_start();
            session_unset();
            session_destroy();
            session_write_close();
            setcookie(session_name(),'',0,'/');
            session_regenerate_id(true);
        }
        // redirect to login page 
        header("Location: /AttendanceMonitor/public/index.php"); 
    }
}