<?php
// delte all session variables
$_SESSION = array();

//delete session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
            session_name()
            , ''
            , time() - 42000
            , $params["path"]
            , $params["domain"]
            , $params["secure"]
            , $params["httponly"]
    );
}

// finally delete session
session_unset();
session_destroy();
?>