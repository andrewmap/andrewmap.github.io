<?php
    ob_start();
    include("db.php");
    include("sessions.php");

    if(checkSession())
    {
        if(checkLogin($_SESSION['user'], $session_name))
        {
            header("Location: index.php");
            die();
        }
    }

    if(isset($_SESSION['block']))
    {
        if(time() < $_SESSION['block'])
        {
            echo("You are blocked for too many atempts until: ".date("m/d/Y - H:i:s", $_SESSION['block']).".");
            die();
        }
    }

    if(isset($_POST['submit']))
    {
        if(isset($_POST['user']) && isset($_POST['password']))
        {
            $user = strtoupper($_POST['user']);
            $password = $_POST['password'];

            $reg = preg_match("/^[a-z0-9A-Z]+$/", $user);
            if($reg !== 1)
            {
                $errorMessage = "Invalid user or password. (-EX00L234)";
                logMessage($db, "User failed at regular expression.", "Login");
            }
            else
            {
                try
                {
                    $stmt = $db->prepare("SELECT password, login_tries, block_until FROM users WHERE user=:user");
                    $stmt->bindParam(":user", $user);
                    $stmt->execute();

                    $data = $stmt->fetch(PDO::FETCH_ASSOC);

                    if(!empty($data))
                    {
                        if(time() >= $data['block_until'])
                        {
                            if(password_verify($password, $data['password']))
                            {
                                $ip = $_SERVER['REMOTE_ADDR'];
                                $ua = $_SERVER['HTTP_USER_AGENT'];

                                $expires = time() + (4 * 60 * 60); // agora + 4 horas
                                $time = time();

                                $session_hash = $user."yE6uuPNza52VwD43J4ABXrSq";
                                $session_hash = password_hash($session_hash, PASSWORD_DEFAULT);

                                $token = bin2hex(random_bytes(64));

                                //session_destroy();
                                //session_name($session_name);
                                //session_set_cookie_params(0, "/", $_SERVER['SERVER_NAME'], false, true);
                                //session_start();
                                //session_regenerate_id();

                                $_SESSION['ip'] = $ip;
                                $_SESSION['ua'] = $ua;
                                $_SESSION['session_hash'] = $session_hash;
                                $_SESSION['token'] = $token;
                                $_SESSION['expires'] = $expires;
                                $_SESSION['user'] = $user;

                                logMessage($db, "Successful login.", "Login");

                                $stmt = $db->prepare("INSERT INTO sessions (user, session_hash, token) VALUES (:user, :session_hash, :token)");

                                $stmt->bindParam(":user", $user);
                                $stmt->bindParam(":session_hash", $session_hash);
                                $stmt->bindParam(":token", $token);


                                $stmt->execute();

                                $session_db_id = $db->lastInsertId();

                                $_SESSION['sess_db_id'] = $session_db_id;

                                $stmt = $db->prepare("UPDATE users SET login_tries=0, block_until=0, last_login=:last_login WHERE user=:user");

                                $stmt->bindParam(":last_login", $time);
                                $stmt->bindParam(":user", $user);
                                $stmt->execute();

                                $_SESSION['block'] = 0;

                                header("Location: index.php");
                                die();
                            }
                            else
                            {
                                $errorMessage = "Invalid user or password. (-Ex00L178)";

                                $tries = $data['login_tries'] + 1;
                                $minutos = 15;
                                $_SESSION['user'] = $user;

                                if($data['login_tries'] >= 3)
                                {
                                    $stmt = $db->prepare("UPDATE users SET login_tries=0, block_until=:block_until WHERE user=:user");

                                    $block_until = time() + ($minutos * 60);
                                    $stmt->bindParam(":block_until", $block_until);
                                    $stmt->bindParam(":user", $user);
                                    $stmt->execute();

                                    $_SESSION['block'] = $block_until;
                                    $errorMessage = "You are blocked for too many attempts until: ".date("d/m/Y - H:i:s", $block_until).".";
                                    logMessage($db, "Invalid password. (Blocked for $minutos minutes)", "Login");
                                }
                                else
                                {
                                    $stmt = $db->prepare("UPDATE users SET login_tries=:login_tries WHERE user=:user");
                                    $stmt->bindParam(":login_tries", $tries);
                                    $stmt->bindParam(":user", $user);
                                    $stmt->execute();
                                    logMessage($db, "Invalid password. (Tries: $tries)", "Login");
                                }

                            }
                        }
                        else
                        {
                            $errorMessage = "You are blocked for too many atempts until: ".date("d/m/Y - H:i:s", $data['block_until']).".";
                        }
                    }
                    else
                    {
                        $errorMessage = "Invalid user or password. (-Ex00L832)";
                        logMessage($db, "User [$user] not found.", "Login");
                    }
                }
                catch(PDOException $e)
                {
                    echo("<p>Database error: ".$e->getMessage()."</p>");
                    die();
                }
            }
        }
        else
        {
            $errorMessage = "You must fill the fields.";
        }
    }
?>
<!DOCTYPE html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>STAR [DEV]</title>

		<!--
			Anti Google
			Retirar depois
		-->
		<meta name="robots" content="noindex, nofollow">
		<link rel="icon" href="img/stklogo2.png">

        <?php include("css-scripts.php"); ?>
	</head>
	<body class="login-form">



        <h1>STK Capital <span>S.T.A.R.</span></h1>
        <form method="POST" action="login.php">
            <?php
            if(isset($errorMessage))
                echo('<div class="message message-error">'.$errorMessage.'</div>');
            ?>
            <?php
            if(isset($_SESSION['message']))
                echo('<div class="message message-error">'.$_SESSION['message'].'</div>');
            ?>

            <div class="label">Login</div>
            <input type="text" name="user" maxlength="32" id="user" style="text-transform: uppercase">

            <div class="label">Password</div>
            <input type="password" name="password" id="password">

            <input type="submit" name="submit" value="Sign In">
        </form>
    </body>
</html>
<?php
    ob_end_flush();
?>
