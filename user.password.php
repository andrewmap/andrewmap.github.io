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

		<title>Change Password - STAR [DEV]</title>

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

        <?php
        if(isset($_GET['changeyourpassword']))
            $change = true;
        else
            $change = false;

        if(isset($_POST['submit']))
            $change = false;
        ?>

        <section <?php echo(($change) ? "class=\"section_changeyourpassword\"" : ""); ?>>
            <h1>Change Password</h1>

            <?php
            if($change)
                echo("<div class=\"message message-error really-pay-attention\"><i class=\"fas fa-exclamation-triangle\"></i> Atenção: você ainda NÃO trocou sua senha, favor trocar. Mínimo 8 caracteres.</div>");

            if(isset($_POST['submit']))
            {
                if(isset($_POST['password']) &&
                    isset($_POST['password2']))
                {
                    if(isset($_POST['user']))
                        $user = $_POST['user'];
                    else
                        $user = $_SESSION['user'];

                    if(isset($_POST['password_now']))
                        $password_now = $_POST['password_now'];
                    else
                        $password_now = "a";

                    $password = $_POST['password'];
                    $password2 = $_POST['password2'];

                    $reg = preg_match("/^[a-z0-9A-Z]+$/", $user);
                    if($reg !== 1)
                    {
                        echoMessage("error", "There is an inconsistency with your username, please login again.");
                        die();
                    }
                    else
                    {
                        if($password == $password2)
                        {
                            try
                            {
                                $stmt = $db->prepare("SELECT password FROM users WHERE user=:user");
                                $stmt->bindParam(":user", $user);
                                $stmt->execute();

                                $userData = $stmt->fetch();

                                if(password_verify($password_now, $userData['password']) || $_SESSION['user'] == "MASTER")
                                {
                                    $hash_password = password_hash($password, PASSWORD_DEFAULT);

                                    $stmt = $db->prepare("UPDATE users SET password=:password WHERE user=:user");
                                    $stmt->bindParam(":password", $hash_password);
                                    $stmt->bindParam(":user", $user);

                                    $stmt->execute();

                                    echoMessage("ok", "Password changed for user [$user].");
                                    logMessage($db, "Password changed for user [$user].", "User Password");
                                }
                                else
                                {
                                    echoMessage("error", "Current password is incorrect.");
                                }
                            }
                            catch(PDOException $e)
                            {
                                echoMessage("error", "Database error: ".$e->getMessage());
                                die();
                            }
                        }
                        else
                        {
                            echoMessage("error", "You typed different passwords.");
                        }
                    }
                }
            }

            if($_SESSION['user'] == "MASTER")
            {
                try
                {
                    $stmt = $db->prepare("SELECT user FROM users ORDER BY id DESC");
                    $stmt->execute();
                    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                catch(PDOException $e)
                {
                    echoMessage("error", "Database error: ".$e->getMessage());
                    die();
                }
            }

            $action = "user.password.php";
            if($change)
                $action .= "?changeyourpassword";
            ?>

            <form method="POST" id="userforms" action="<?php echo($action); ?>" onsubmit="return checkForm()">
                <?php 
                if(isset($users))
                {
                    echo("<p>Select user:</p>
                    <select name=\"user\" id=\"user\">");
                    foreach($users as $user)
                    {
                        echo("<option value=\"".$user['user']."\">".$user['user']."</option>");
                    }
                    echo("</select>");
                }
                ?>

                <p>Current password:</p>
                <input type="password" name="password_now" id="password_now" <?php echo(($_SESSION['user'] == "MASTER") ? "disabled" : ""); echo(($change) ? "value=\"stkcapital2018\"" : ""); ?>>

                <p>New password:</p>
                <input type="password" name="password" id="password">

                <p>Again to confirm:</p>
                <input type="password" name="password2" id="password2">

                <input type="submit" name="submit" value="Change Password">
            </form>
        </section>

        <script>
        function checkForm()
        {
			var user = $('#user option:selected').val();
			var password_now = $('#password_now').val();
			var password = $('#password').val();
			var password2 = $('#password2').val();

            if(password_now.length == 0 && user == undefined)
            {
                alert("You need to type your current password.");
                return false;
            }

            if(password.length == 0 || password2.length == 0 || password.length < 4 || password2.length < 4)
            {
                alert("The new password must be at least 4 characters long.");
                return false;
            }

            if(password != password2)
            {
                alert("You typed different passwords.");
                return false;
            }

            return true;
        }
        </script>
    </body>
</html>
<?php
ob_end_flush();
?>
