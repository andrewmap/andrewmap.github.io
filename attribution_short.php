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
		-->
		<meta name="robots" content="noindex, nofollow">
		<link rel="icon" href="img/stklogo2.png">

        <?php include("css-scripts.php"); ?>
	</head>
	<body>
		<?php include("header.php"); ?>


		<div style="height:1200px; margin-top:50px;width:100%;right:100px" >

        <iframe 
            width="100%" 
            height="100%" 
            frameborder="0" 
			src=<?php echo "https://stkcapital.metabaseapp.com/public/dashboard/a7d98036-c522-473e-85d3-a074188d4c52?single_date=" . $lastWorkingDay . "#refresh=60";?> 
            title="Attribution Short STK"
        ></iframe>
		
		</div>
    </body>
</html>
<?php
ob_end_flush();
?>
