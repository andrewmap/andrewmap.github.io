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

		<title>User Password Check - STAR [DEV]</title>

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

        <section>
            <h1>User Password Check</h1>
            <pre><?php
                try
                {
                    $stmt = $db->prepare("SELECT user, password FROM users ORDER BY id DESC");
                    $stmt->execute();
                    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach($users as $user)
                    {
                        $changed = (password_verify("stkcapital2018", $user['password'])) ? "NO" : "YES";
                        if($changed == "NO")
                            $color = "red";
                        else
                            $color = "lime";
                        
                        $changed = "<span style=\"color: $color\">$changed</span>";

                        echo("<p>User: {$user['user']} - Changed default password: $changed</p>");
                    }
                }
                catch(PDOException $e)
                {
                    echoMessage("error", "Database error: ".$e->getMessage());
                    die();
                }
            ?></pre>
        </section>
    </body>
</html>