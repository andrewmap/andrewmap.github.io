<?php
    if(!checkSession())
    {
        header("Location: login.php");
        die();
    }
    if(!checkLogin($_SESSION['user'], $session_name))
    {
        header("Location: login.php");
        die();
    }
    
?>
