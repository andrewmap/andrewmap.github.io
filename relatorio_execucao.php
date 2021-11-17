<?php
    ob_start();
    include("db.php");
    include("sessions.php");
    include("login.check.php");


	$lastWorkingDay = (date("N")<=5)?date("Y-m-d"):date("Y-m-d",strtotime('today'))	
	$EnderecoMetabase = <?php echo "https://stkcapital.metabaseapp.com/public/dashboard/b9dc8977-9a4f-46fd-aa97-5f107f3d789e?single_date=" . $lastWorkingDay . "#refresh=5";?>
?>
<!DOCTYPE html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Relatorio Execução</title>

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


		<div style="height:3500px; margin-top:50px" >
			
        <iframe width="100%" height="100%" frameborder="10" src=<?php echo "https://stkcapital.metabaseapp.com/public/dashboard/025bbf62-15ff-4892-b15b-95dc244bc9d7?single_date=" . $lastWorkingDay . "#refresh=5";?> title="Relatorio Execução STK"></iframe>
		
		</div>
    </body>
</html>
<?php
ob_end_flush();
?>
