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
		<!-- <meta http-equiv="refresh" content="65"/> -->
		<meta http-equiv="refresh" content="1; URL=http://stkcapital.com.br/star/pl_real_time.php" />
		<title>STK PL Real-Time - STAR [DEV]</title>

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


		<section class="section_update_brazil">
			<h1>STK PL Real-Time</h1>

			<?php
			$file = "json/update_Brazil.json";

			if($_SESSION['user'] == "VH")
			{
				//$file = "json/update_Brazil - Copy.json";
				//$file = "json/update_Brazil.json";
			}
			$jsonContents = file_get_contents($file);
			$modified = date("d/m/Y G:i:s", filemtime($file));

			$json = json_decode($jsonContents, true);

			while(empty($json)){
				$jsonContents = file_get_contents($file);
				$modified = date("d/m/Y G:i:s", filemtime($file));
				$json = json_decode($jsonContents, true);
			}

			$funds = $json['Funds'][0];
			$top15 = $json['Top 15 Performance (in bps)'][0];
			$bottom15 = $json['Bottom 15 Performance (in bps)'][0];
			$pairs = $json['Pair Trades'][0];
			$comdty = $json['Commodities'][0];
			?>

			<p>Last update: <?php echo($modified); ?></p>
			<?php
				foreach ($funds as $key=>$f)
				{
					if($key == "Trade")
						foreach($f[0] as $jey=>$f2)
						{
							if($f2 == "x")
							echo("<span style='background-color:yellow;height:20px;'>Variações inconsistentes, verifique com atenção os números</span>");
						}
				}
			?>
			<table class="update_brazil">
				<tr>
					<th>Funds</th>
					<th>STK LB</th>
					<th>STK LO</th>
					<th>Total</th>
				</tr>
				<?php
				$idx = "";
				foreach ($funds as $key=>$f)
				{
					if(strpos($key, "bps") !== FALSE)
						echo("<tr class=\"update_brazil_highlight\">");
					else
						echo("<tr>");

					echo("<td>{$key}</td>");


					foreach($f[0] as $jey=>$f2)
					{
						if($f2 == "NULL" || $f2 == "0")
							$f2 = "";

						if(strpos($f2, "-") !== FALSE)
							$f2 = "<span style=\"color: red\">$f2</span>";
						if(strpos($f2, "(") !== FALSE)
							$f2 = "<span style=\"color: red\">$f2</span>";

						echo("<td>{$f2}</td>");
					}

					echo("</tr>");
					if($key == "Month to Date F (in bps)")
					{
						/*echo("<tr>
								<td>Index</td>
								<td>IBOV</td>
								<td>BRL</td>
								<td>IBX</td>
							</tr>");*/
					}
				}
				?>
			</table>

			<h2>Top 15 Performance (in bps)</h2>
			<table class="update_brazil">
				<tr>
					<th>Ticker</th>
					<th>Last Price</th>
					<th>STK LB</th>
					<th>STK LO</th>
					<th>% Stock Chg</th>
				</tr>
				<?php
				foreach ($top15 as $key=>$f)
				{
					echo("<tr>");

					echo("<td>{$key}</td>");

					foreach($f[0] as $jey=>$f2)
					{
						if($f2 == "NULL" || $f2 == "0")
							$f2 = "";

						echo("<td>{$f2}</td>");
					}

					echo("</tr>");
				}
				?>
			</table>

			<h2>Bottom 15 Performance (in bps)</h2>
			<table class="update_brazil">
				<tr>
					<th>Ticker</th>
					<th>Last Price</th>
					<th>STK LB</th>
					<th>STK LO</th>
					<th>% Stock Chg</th>
				</tr>
				<?php
				foreach ($bottom15 as $key=>$f)
				{
					echo("<tr>");

					echo("<td>{$key}</td>");

					foreach($f[0] as $jey=>$f2)
					{
						if($f2 == "NULL" || $f2 == "0")
							$f2 = "";

						echo("<td>{$f2}</td>");
					}

					echo("</tr>");
				}
				?>
			</table>

			<h2>Pair Trades</h2>
			<table class="update_brazil">
				<tr>
					<th>Ticker</th>
					<th>Bps</th>
					<th>%Stock Chg</th>
					<th>Ratio</th>
					<th>% to Gain</th>
				</tr>
				<?php
				foreach ($pairs as $key=>$f)
				{
					echo("<tr>");

					echo("<td>{$key}</td>");

					foreach($f[0] as $jey=>$f2)
					{
						if($f2 == "NULL" || $f2 == "0")
							$f2 = "";

						if(strpos($f2, "-") !== FALSE)
							$f2 = "<span style=\"color: red\">$f2</span>";

						echo("<td>{$f2}</td>");
					}

					echo("</tr>");
				}
				?>
			</table>
			<div>&nbsp</div>
			<div style="border: 1px solid black;top:20px;">
				<p >Parâmetros:</br>Variações maiores que 3% em mais de 3 papeis ocorre no alerta em amarelo.
				</br>Horário de Leilão: variações e preços podem ficar instáveis
				</p>

			</div>
			<div>
				<a href="mailto:ap@stkcapital.com.br?subject=ERRO PL&message=Ver PL Erro">SEND EMAIL ERRO</a>
			</div>

			<?php
			//echo("<pre>");
			//print_r($pairs);
			//echo("</pre>");
			?>

		</section>
    </body>
</html>

<?php
ob_end_flush();
?>
