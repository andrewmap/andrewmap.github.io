<?php
    ob_start();
    include("db.php");
    include("sessions.php");
    include("login.check.php");
	$lastWorkingDay = (date("N")<=5)?date("Y-m-d"):date("Y-m-d",strtotime('today'))	;
	
?>



<!DOCTYPE html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Valor Relativo - STAR [DEV]</title>

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
			<h1>Valor Relativo</h1>
            <h2>Funds</h2>
            <div class="image-documents-grid">

				<a href=<?php echo "https://stkcapital.metabaseapp.com/public/dashboard/025bbf62-15ff-4892-b15b-95dc244bc9d7?data=" . $lastWorkingDay ;?> target="_blank">
				<div class="image-document">
						<img src="img/documents/comparativo_fundos_2.png">
						<p>Carteira Pares </p>
					</div>
				</a>

				<a href=<?php echo "https://stkcapital.metabaseapp.com/public/dashboard/6b61dce0-e6fd-4c81-9f1e-b9afc427933a?data=" . $lastWorkingDay ;?> target="_blank">
				<div class="image-document">
						<img src="img/documents/comparativo_fundos_2.png">
						<p>Relatorio Teste Estatístico </p>
					</div>
				</a>
            
			
			</div>

			
		
		</section>
    </body>
</html>

<?php
ob_end_flush();
?>
