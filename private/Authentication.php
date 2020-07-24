<?php

class Authentication
{
    public function handleLogin($db, $username, $password)
    {
        // check db to see if input matches a sid entry
        if ($query = $db->prepare("SELECT sid, password, name FROM students WHERE sid = :sid")) {
            $query->bindParam(":sid", $username);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);

            if (!empty($result)) {
                $hash = $result["password"];

                // compare hash of password from form with hash stored in the database
                if (md5($password) === $hash) {
                    // create new session and assign values
                    session_regenerate_id(true);
                    $_SESSION["loggedin"] = true;

                    // privilege key prevents student from entering lecturer/manager URLs in browser and accessing their pages
                    $_SESSION["privilege"] = "student";

                    // name and id are stored to reduce the no. of db queries required
                    $_SESSION["name"] = $result["name"];
                    $_SESSION["sid"] = $result["sid"];

                    // redirect to homepage
                    header("Location: /AttendanceMonitor/public/student/homepage_student.php");
                } else {
                    // send message back to the landing page
                    $message = "Incorrect password. Please try again.";
                    header("Location: /AttendanceMonitor/public/index.php?error_message=" . $message);
                }
            } else {
                // check db to see if input matches a staff username entry
                if ($query = $db->prepare("SELECT username, password, name, position FROM staff WHERE username = :username")) {
                    $query->bindParam(":username", $username);
                    $query->execute();
                    $result = $query->fetch(PDO::FETCH_ASSOC);

                    if (!empty($result)) {
                        $hash = $result["password"];
                    
                        // compare hashes
                        if (md5($password) === $hash) {
                            // create new session and assign values
                            session_start();
                            session_regenerate_id(true);

                            $_SESSION["loggedin"] = true;
                            $position = $result["position"];
                            
                            // route staff based on position
                            if ($position == "lecturer") {
                                $_SESSION["privilege"] = "lecturer";
                                $_SESSION["name"] = $result["name"];
                                header("Location: /AttendanceMonitor/public/lecturer/homepage_lecturer.php");
                            } elseif ($position == "manager") {
                                $_SESSION["privilege"] = "manager";
                                $_SESSION["name"] = $result["name"];
                                header("Location: /AttendanceMonitor/public/manager/homepage_manager.php");
                            }
                        } else {
                            $error_message = "Incorrect password. Please try again.";

                            // sends error_message back to the landing page that can be retrieved with $_GET
                            header("Location: /AttendanceMonitor/public/index.php?error_message=" . $error_message);
                        }
                    } else {
                        $error_message = "Incorrect username. Please try again.";
                        header("Location: /AttendanceMonitor/public/index.php?error_message=" . $error_message);
                    }
                }
            }
        } else {
            $error_message = "Incorrect student ID. Please try again.";
            header("Location: /AttendanceMonitor/public/index.php?error_message=" . $error_message);
        }
    }

    public function verifySID($db, $sid)
    {
        if ($query = $db->prepare("SELECT sid FROM students WHERE sid = :sid")) {
            $query->bindParam(":sid", $sid);
            $query->execute();
            $result = $query->fetch();

            if (!empty($result)) {
                session_start();
                session_regenerate_id(true);
                $_SESSION["sid"] = $sid;
                $_SESSION["sidVerified"] = true;

                // purge this var once error logs in fully
                if (isset($_SESSION["errorMessage"])) {
                    $message= "Incorrect Student ID. Please try again.";
                }
            }

            header("Location: /AttendanceMonitor/public/signup.php?message=" . $message);
        }
    }

    public function createAndStorePassword($db, $sid, $password)
    {
        if ($query = $db->prepare("UPDATE students SET password = :hash WHERE sid = :sid"));
        {
            $hash = md5($password);
            $query->bindParam(":hash", $hash);
            $query->bindParam(":sid", $sid);
            $query->execute();

            $success_message = "Password successfully created";
        }
        
        header("Location: /AttendanceMonitor/public/index.php?success_message=" . $success_message);
    }


    public function printSignUpForm()
    {
        if (!$_SESSION["sidVerified"]) {
            if (isset($_SESSION["errorMessage"])) {
                echo '<h3>Incorrect student ID. Please try again.</h3>';
            }
            // this prints the initial signup form that verifies the student's ID
            echo '
            <div class="login-form">
            <h3 class="login-header">Enter Student ID</h3>
            <form action="../private/new_user.php" method="post" id="studentIDForm"> 
                <div class="login-textbox">
                <label id="sidLabel"></label>
                <input type="text" name="sid" placeholder="Student ID" id="sidInput" autofill="off" required/>
                <button class="login-button" type="submit" class="">Submit</button> 
                </div>
            </form> 
            </div> 
            ';
        }

        // this prints the create password form once the student ID has been verified
        else {
            echo '
            <div class="login-form"> 
            <h3 class="login-header">Create Password</h3> 
            <form action="/AttendanceMonitor/private/create_password.php" method="post" id="passwordForm">
            <div class="login-textbox">
            <label id="passwordLabel"></label>
            <input type="password" name="password" placeholder="Password" id="passwordInput" class="hidden" required autofill="off">
            <input type="password" name="confirmPassword" placeholder="Confirm Password" id="confirmPassword" class="hidden" required autofill="off"/>
            </div> 
            <button class="login-button" type="submit">Submit</button> 
            </form>
            </div> 
            ';
        }
    }
}
