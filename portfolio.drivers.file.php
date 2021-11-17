<?php
    ob_start();
    include("db.php");
    include("sessions.php");
    include("login.check.php");

    if(!isset($_GET['id']) || !isset($_GET['type']))
    {
        header("Location: research.control.php");
        die();
    }

    if(!is_numeric($_GET['id']))
    {
        echo("<h1>File not found.</h1>");
        die();
    }

    $id = $_GET['id'];
    $type = $_GET['type'];

    try
    {
        $sel = $type."_file";
        $ext = $type."_ext";

        $sql = "SELECT $sel, $ext, Ticker_Referencia FROM portfolio_drivers WHERE id=:id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":id", $id);

        $stmt->execute();

        $info = $stmt->fetch();
        $file = $info[0];
        $ext = $info[1];
        $ticker = $info[2];

        if(empty($file))
        {
            echo("<h1>File not found</h1>");
            die();
        }

        header("Content-type: multipart/form-data");
        header("Content-Disposition: attachment; filename=".$type."_".$ticker.".".$ext);
        print($file);
    }
    catch(PDOException $e)
    {
        echo("Database error: ".$e->getMessage());
        die();
    }

    ob_end_flush();
?>