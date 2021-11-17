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

		<title>Porfolio Universe - STAR [DEV]</title>

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
			<h1>Portfolio Universe</h1>
            <h2>Funds</h2>
            <div class="image-documents-grid">
				<a href="../pdf.php?file=Portfolio_Universe.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/portfolio_universo.png">
						<p>Portfolio Universe</p>
					</div>
				</a>
				<a href="../pdf.php?file=A%20list%20Brazil.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/a_list_brazil2.png">
						<p>[A] List Brazil</p>
					</div>
				</a>
				<a href="https://app.powerbi.com/view?r=eyJrIjoiMWEwZDVkNmUtY2ZlNC00ZWZjLWFhZTEtMGM1ZjRkZmFlODhlIiwidCI6ImM1ODQzODAzLTAyMWUtNDI2ZS04NjY1LTdlNzkyMWYxZjU1OSJ9" target="_blank">
					<div class="image-document">
						<img src="img/documents/calendarico.png">
						<p>Earnings Calendar</p>
					</div>
				</a>
				<a href="https://stkcapital.metabaseapp.com/public/dashboard/fbc963a4-0aa7-42b8-8cbf-cdd6f88bce7f" target="_blank">
					<div class="image-document">
						<img src="img/documents/comparativo_fundos_2.png">
						<p>Comparativo Fundos</p>
					</div>
				</a>

				<a href="attribution.php">
				<div class="image-document">
						<img src="img/documents/comparativo_fundos_2.png">
						<p>Attribution</p>
					</div>
				</a>
    			<a href="attribution_short.php">
				<div class="image-document">
						<img src="img/documents/comparativo_fundos_2.png">
						<p>Attribution Short</p>
					</div>
				</a>
				<a href="relatorio_enquadramento.php">
				<div class="image-document">
						<img src="img/documents/comparativo_fundos_2.png">
						<p>Relatorio Enquadramento LB Master x Prev.</p>
					</div>
				</a>
				<a href="https://stkcapital.metabaseapp.com/public/question/64e46cc3-5f18-40ba-91e9-f84072d64185" target="_blank">
					<div class="image-document">
						<img src="img/documents/comparativo_fundos_2.png">
						<p>Quotes BR</p>
					</div>
				</a>
				<a href="https://stkcapital.metabaseapp.com/public/question/aac41968-817c-483e-bec0-906a9b5216bf" target="_blank">
					<div class="image-document">
						<img src="img/documents/comparativo_fundos_2.png">
						<p>Quotes US</p>
					</div>
				</a>
            </div>
		</section>
    </body>
</html>

<?php
ob_end_flush();
?>
