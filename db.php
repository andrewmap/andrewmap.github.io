<?php 
    /* Config */
    date_default_timezone_set("America/Sao_Paulo");
    $db_ip = "localhost";
    if($_SERVER['REMOTE_ADDR'] == "127.0.0.1")
    {
        $db_name = "stk";
        $db_user = "root";
        $db_pass = "";
    }
    else
    {
        $db_name = "stkcap53_web";
        $db_user = "stkcap53_web";
        $db_pass = "#BGdrE+=fVm.n*VA";
    }
    $db_debug = true;
    $session_name = "STK_SESSION";

    try {
        $db = new PDO("mysql:host=".$db_ip.";dbname=".$db_name.";charset=utf8", $db_user, $db_pass);
        if($db_debug)
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        else
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }
    catch (PDOException $e)
    {
        if($db_debug)
            echo("<p>DB Error: ".$e->getMessage()."</p>");
        die();
    }

    if(!function_exists("logMessage"))
    {
        function logMessage($db, $message, $part, $detail = "")
        {
            $time = time();
            $uip = $_SERVER['REMOTE_ADDR'];
            $user = "unknown";
            if(isset($_SESSION))
                if(isset($_SESSION['user']))
                    $user = $_SESSION['user'];

            try 
            {
                $stmt = $db->prepare("INSERT INTO logs (user, message, detail, date, ip, part) VALUES (:user, :message, :detail, :date, :ip, :part)");
                $stmt->bindParam(":user", $user);
                $stmt->bindParam(":message", $message);
                $stmt->bindParam(":detail", $detail);
                $stmt->bindParam(":date", $time);
                $stmt->bindParam(":ip", $uip);
                $stmt->bindParam(":part", $part);

                $stmt->execute();
            }
            catch (PDOException $e)
            {
                echo("Erro no DB: ".$e->getMessage());
                die();
            }
        }
    }

    if(!function_exists("echoMessage"))
    {
        function echoMessage($type, $msg)
        {
            echo("
                <p class=\"message-box message-$type\">$msg</p>
            ");
        }
    }

    if(!function_exists("lastDate"))
    {
        function lastDate()
        {
            $name = date("l");
            
            if($name == "Monday")
                return time() - (60 * 60 * 24 * 3);
            else
                return time() - (60 * 60 * 24);
        }
    }
?>