<?php
    ob_start();
    include("db.php");
    include("sessions.php");
    include("login.check.php");


	$lastWorkingDay = date( "Y-m-d", strtotime("today -1 Weekday") )	
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
			date( "Y-m-d", strtotime("-1 day") )
		-->
		<meta name="robots" content="noindex, nofollow">
		<link rel="icon" href="img/stklogo2.png">

        <?php include("css-scripts.php"); ?>
	</head>
	<body>
		<?php include("header.php"); ?>


		<div style="height:3800px; margin-top:50px" >
			
        <iframe width="120%" height="100%" frameborder="30" src=<?php echo "https://stkcapital.metabaseapp.com/public/dashboard/d0b1feb0-c5cd-49d7-9664-1a37916724dc?single_date=" . $lastWorkingDay ;?> title="Relatorio Enquadramento STK"></iframe>
		
		</div>
    </body>
</html>
<?php
ob_end_flush();
?>
