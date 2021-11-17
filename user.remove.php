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

		<title>Remove User - STAR [DEV]</title>

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
            <h1>Remove User</h1>
            <?php
            if($_SESSION['user'] != "MASTER")
            {
                echoMessage("error", "This task can only be performed by master user.");
                die();
            }

            if(isset($_POST['submit']))
            {
                if(isset($_POST['id']))
                {
                    $userid = $_POST['id'];

                    $stmt = $db->prepare("DELETE FROM users WHERE id=:id");
                    $stmt->bindParam(":id", $userid);

                    $stmt->execute();

                    echoMessage("ok", "User ID [$userid] removed with success.");
                    logMessage($db, "User ID [$userid] removed.", "User Remove");
                }
            }

            try
                {
                    $stmt = $db->prepare("SELECT user, id FROM users WHERE user <> 'MASTER' ORDER BY id DESC");
                    $stmt->execute();
                    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                catch(PDOException $e)
                {
                    echoMessage("error", "Database error: ".$e->getMessage());
                    die();
                }
            ?>

            <form method="POST" id="userforms" action="user.remove.php" onsubmit="return checkForm()">
                <p>User to remove:</p>
                <?php 
                if(isset($users))
                {
                    echo("<select name=\"id\" id=\"user\">");
                    foreach($users as $user)
                    {
                        echo("<option value=\"".$user['id']."\">".$user['user']."</option>");
                    }
                    echo("</select>");
                }
                ?>

                <input type="submit" name="submit" value="Remove User">
            </form>

            <script>
            function checkForm()
            {
                
                if(confirm("Confirm deletion of the selected user?"))
                {
                    return true;
                }

                return false;
            }
            </script>
        </section>
    </body>
</html>

<?php
ob_end_flush();
?>