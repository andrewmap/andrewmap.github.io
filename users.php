<?php
	ob_start();
    include("db.php");
    include("sessions.php");
    include("login.check.php");
?>
<!DOCTYPE html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Users - STAR [DEV]</title>

		<!-- 
			Anti Google
			Retirar depois
		-->
		<meta name="robots" content="noindex, nofollow">
		<link rel="icon" href="img/stklogo2.png">
		
        <?php include("css-scripts.php"); ?>
	</head>
	<body>
        <?php include("header.php"); ?>


        <section class="table-overflow section_update_brazil">
            <h1>Users</h1>

            <?php
            try
            {
                $stmt = $db->prepare("SELECT user, last_login, last_click, login_tries, block_until FROM users ORDER BY user ASC");
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            catch(PDOException $e)
            {
                echoMessage("error", "Database error: ".$e->getMessage());
                die();
            }
            ?>

            <pre><?php
                foreach($users as $user)
                {
                    $stmt = $db->prepare("SELECT count(*) FROM logs WHERE user=:user AND message='Successful login.'");
                    $stmt->bindParam(":user", $user['user']);
                    $stmt->execute();
                    $count = $stmt->fetchColumn();

                    $stmt = $db->prepare("SELECT ip FROM logs WHERE user=:user ORDER BY id DESC LIMIT 1");
                    $stmt->bindParam(":user", $user['user']);
                    $stmt->execute();
                    $last_ip = $stmt->fetchColumn();

                    $last_login = ($user['last_login'] != 0) ? date("d/m/y G:i:s", $user['last_login']) : "-";
                    $last_click = ($user['last_click'] != 0) ? date("d/m/y G:i:s", $user['last_click']) : "-";
                    $block_until = ($user['block_until'] != 0) ? date("d/m/y G:i:s", $user['block_until']) : "-";
                    echo("User: ".$user['user']."\n");
                    echo("Last Login: ".$last_login."\n");
                    echo("Last click: ".$last_click."\n");
                    echo("Login tries: ".$user['login_tries']."\n");
                    echo("Block until: ".$block_until."\n");
                    echo("Accessed ".$count." times\n");
                    echo("Last IP: ".$last_ip."\n");
                    echo("-------------\n");
                }
                ?></pre>
            
        </section>

    </body>
</html>
<?php
ob_end_flush();
?>
