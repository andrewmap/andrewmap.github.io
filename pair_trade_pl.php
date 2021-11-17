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
		<meta http-equiv="refresh" content="30"/>
		<title>Comparativo Fundos Real-Time - STAR [DEV]</title>

	
		<meta name="robots" content="noindex, nofollow">
		<link rel="icon" href="img/stklogo2.png">

        <?php include("css-scripts.php"); ?>
	</head>
	<body>
		<?php include("header.php"); ?>


		<section class="section_update_brazil">
			<h1>Relatório Comparativo Fundos</h1>
			
			<iframe
    			src="https://stkcapital.metabaseapp.com/public/dashboard/fbc963a4-0aa7-42b8-8cbf-cdd6f88bce7f"
    			frameborder="0"
    			width="1500"
    			height="1500"
    			allowtransparency
			></iframe>

		</section>
		
		
    </body>
</html>

<?php
ob_end_flush();
?>