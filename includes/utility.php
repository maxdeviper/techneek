<?php
//this file contains the session start to start a session and must be placed at the top of any html

    session_start();

//
function checkLogin()
{
    if (isset($_SESSION["email"])) {
        return true;
    }else{
      return false;
    }
}

//it also contains a function to logout the user look for a way to apply this like add the logout logic in a php file and link an anchor tag or button to submit to that file and after execution redirect the user to the home screen
// $_SESSION can only be called after session_start() is used;
function logout()
{
    global $_SESSION;
    $_SESSION = array();
    if (ini_get("session_use_cookies")) {
        $param = session_get_cookie_params();
        setcookie(session_name(), '', time() - 4200, $param["path"], $param["domain"], $param["secure"], $param["httponly"]);
    }
    session_destroy();
    header("Location:login.php");
    exit();
}
    