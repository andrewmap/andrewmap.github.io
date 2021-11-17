<?php
    session_name("STK_SESSION");
	session_set_cookie_params(0, "/", $_SERVER['SERVER_NAME'], false, true);
    session_start();
    $_SESSION = array();
    session_destroy();
    header("Location: login.php");
?>