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

		<title>Registry - STAR [DEV]</title>

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
            <h1>Registry</h1>
            <?php

            try
            {
                $stmt = $db->prepare("SELECT id, user, message, date, ip, part, detail FROM logs ORDER BY id DESC LIMIT 1000");
                $stmt->execute();
                $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            catch(PDOException $e)
            {
                echoMessage("error", "Database error: ".$e->getMessage());
                die();
            }
            ?>

            <p><a href="user.check.password.php">User password check</a></p>
            <table class="registry">
                <tr>
                    <th>User</th>
                    <th>Location</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>IP</th>
                    <th>Detail</th>
                </tr>
                <?php
                foreach($logs as $log)
                {
                    echo("
                        <tr>
                            <td>{$log['user']}</td>
                            <td>{$log['part']}</td>
                            <td>{$log['message']}</td>
                            <td>".date("d/m/y - G:i:s", $log['date'])."</td>
                            <td>{$log['ip']}</td>
                    ");

                    if(!empty($log['detail']))
                        echo("<td><i class=\"fas fa-info fa-fw\" id=\"{$log['id']}\" onclick=\"openDetail($(this));\"></i></td>");
                    else
                        echo("<td></td>");
                    
                    echo("</tr>");
                }
                ?>
            </table>
            <script>
            function openDetail(e) {
                var logId = e.attr('id');
                window.open("registry.detail.php?id="+logId, "", "width=600,height=600");
            }
            </script>
        </section>
    </body>
</html>
<?php
ob_end_flush();
?>