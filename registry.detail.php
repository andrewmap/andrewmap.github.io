<?php
    include("db.php");
    include("sessions.php");
    include("login.check.php");
?>
<!DOCTYPE html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Registry Detail - STAR [DEV]</title>

		<!-- 
			Anti Google
			Retirar depois
		-->
		<meta name="robots" content="noindex, nofollow">
		<link rel="icon" href="img/stklogo2.png">
		
        <?php include("css-scripts.php"); ?>
	</head>
	<body class="registry-popup">
        <?php
        if(!isset($_GET['id']))
        {
            echo("You can't access this page manually.");
            die();
        }
        if(!is_numeric($_GET['id']))
        {
            echo("You can't access this page manually.");
            die();
        }
        
        $id = $_GET['id'];

        try
        {
            $stmt = $db->prepare("SELECT user, part, message, date, ip, detail FROM logs WHERE id=:id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            
            $log = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            echo("Erro no banco de dados: ".$e->getMessage());
            die();
        }

        $data = date("d/m/y G:i", $log['date']);

        echo("<h1>Registry Detail</h1>
        
        <p>User: <b>{$log['user']}</b></p>
        <p>Where: <b>{$log['part']}</b></p>
        <p>Date: <b>{$data}</b></p>
        <p>IP: <b>{$log['ip']}</b></p>
        <p>Registry: <br><b>{$log['message']}</b></p>
        <br>
        <h3>Detail:</h3>
        <p>".html_entity_decode(urldecode($log['detail']))."</p>");
        ?>
    </body>
</html>