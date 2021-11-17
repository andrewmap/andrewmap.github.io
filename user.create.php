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

		<title>Create User - STAR [DEV]</title>

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
            <h1>Create User</h1>
            <?php

            if($_SESSION['user'] != "MASTER")
            {
                echoMessage("error", "This task can only be performed by master user.");
                die();
            }

            if(isset($_POST['submit']))
            {
                if(isset($_POST['user']) && 
                    isset($_POST['password']) &&
                    isset($_POST['password2']))
                {
                    $user = $_POST['user'];
                    $password = $_POST['password'];
                    $password2 = $_POST['password2'];

                    $reg = preg_match("/^[a-z0-9A-Z]+$/", $user);
                    if($reg !== 1)
                    {
                        echoMessage("error", "The user field must contain only characters and numbers.");
                    }
                    else
                    {
                        if($password == $password2)
                        {
                            $hash_password = password_hash($password, PASSWORD_DEFAULT);

                            $stmt = $db->prepare("INSERT INTO users (user, password) VALUES (:user, :password)");
                            $stmt->bindParam(":user", $user);
                            $stmt->bindParam(":password", $hash_password);

                            $stmt->execute();

                            echoMessage("ok", "User [$user] created with success.");
                            logMessage($db, "User [$user] created.", "User Create");
                        }
                        else
                        {
                            echoMessage("error", "You typed different passwords.");
                        }
                    }
                }
            }
            ?>

            <form method="POST" id="userforms" action="user.create.php" onsubmit="return checkForm()">
                <p>User/login:</p>
                <input type="text" name="user" id="user">

                <p>Password:</p>
                <input type="password" name="password" id="password">

                <p>Password again:</p>
                <input type="password" name="password2" id="password2">

                <input type="submit" name="submit" value="Create User">
            </form>

            <script>
            function checkForm()
            {
                var user = $('#user').val();
                var password = $('#password').val();
                var password2 = $('#password2').val();

                var regUser = /^[a-z0-9A-Z]+$/i;
                if(!regUser.test(user))
                {
                    alert("The user field must contain only characters and numbers.");
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
        </section>
    </body>
</html>

<?php
ob_end_flush();
?>