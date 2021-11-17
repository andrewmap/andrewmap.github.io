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

		<title>Attribution STK</title>

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


		<div style="height:10200px; margin-top:50px" >
			
        <iframe width="100%" height="100%" frameborder="0" src=<?php echo "https://stkcapital.metabaseapp.com/public/dashboard/bfb9db13-825a-48a2-9e04-0d8a930816cf?single_date=" . date("Y-m-d") . "#refresh=60";?> title="Attribution STK"></iframe>

		</div>
    </body>
</html>
<?php
ob_end_flush();
?>
