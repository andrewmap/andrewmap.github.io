<?php
	ob_start();
    include("db.php");
    include("sessions.php");
    include("login.check.php");

    $_SESSION['expires'] = 0;
    echo("Session expires = 0");

    ob_end_clean();
?>