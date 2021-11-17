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
		<meta http-equiv="refresh" content="65"/>
		<script src="https://kit.fontawesome.com/12fcac9c2d.js" crossorigin="anonymous"></script>
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
			$file = "json/pl_real_time.json";

			$jsonContents = file_get_contents($file);
			$modified = date("d/m/Y G:i:s", filemtime($file));

			$json = json_decode($jsonContents, true);

			while(empty($json)){
				$jsonContents = file_get_contents($file);
				$modified = date("d/m/Y G:i:s", filemtime($file));
				$json = json_decode($jsonContents, true);
			}

			$perf = $json['PERF'][0];
			$funds = $json['Funds'][0];
			$top10lb = $json['Top10_LB'][0];
			$bottom10lb = $json['bottom10_LB'][0];
			$top10lo = $json['top10_LO'][0];
			$bottom10lo = $json['bottom10_LO'][0];
			$top10vr = $json['top10_VR'][0];
			?>

			<p>Last update: <?php echo($modified); ?> <span style="color:rgb(0,80,130)"> <i class="fas fa-sync fa-spin fa-w-16"></i></span></p>
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

			<table class="perf">
				<tr>
					<th>Performance (in bps)</th>
					<th>Today</th>
					<th>MTD</th>
					<th>YTD</th>
					<th>AuM (R$ M) | Price</th>
				</tr>
				<?php
				$idx = "";
				foreach ($perf as $key=>$f)
				{
					if(strpos($key, "bps") !== FALSE)
						echo("<tr class=\"update_brazil_highlight\">");
					else
						echo("<tr>");

					echo("<td>{$key}</td>");


					foreach($f[0] as $jey=>$f2)
					{
						if($f2 == "NULL" || $f2 == "11,111.00" || $f2 == "11,111" || $f2 == "0.0%")
							$f2 = "";

						if(strpos($f2, "-") !== FALSE)
							$f2 = "<span style=\"color: red\">$f2</span>";
						if(strpos($f2, "(") !== FALSE)
							$f2 = "<span style=\"color: red\">$f2</span>";			
						
						echo("<td>{$f2}</td>");
					}

					echo("</tr>");
				}
				?>
			</table>
			

			<table class="prt">
				<tr>
					<th>Strategy</th>
					<th colspan="2"><span style="color:#005082">Long Biased</span></th>
					<th colspan="2"><span style="color:#005082">Long Biased Prev</span></th>
					<th colspan="2"><span style="color:#005082">Long Biased FIM</span></th>
					<th colspan="2"><span style="color:#806000">Long Only</span></th>
				</tr>
                <tr>
					<td>&nbsp</td>
					<td>Exposure</td>
					<td>Contribution</td>
					<td>Exposure</td>
					<td>Contribution</td>
					<td>Exposure</td>
					<td>Contribution</td>
					<td>Exposure</td>
                    <td>Contribution</td>
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
						if($f2 == "NULL" || $f2 == "0.0%" || $f2 == "0" )
							$f2 = "";

						if(strpos($f2, "-") !== FALSE)
							$f2 = "<span style=\"color: red\">$f2</span>";
						if(strpos($f2, "(") !== FALSE)
							$f2 = "<span style=\"color: red\">$f2</span>";
						
						if($key == "NET" && $f2!="11,111" || $key == "GROSS" && $f2!="11,111")
							echo("<td colspan=\"2\" style=\"width: 99%\">{$f2}</td>");
						elseif($f2!="11,111")
							echo("<td>{$f2}</td>");
					}

					echo("</tr>");
				}
				?>
			</table>

			<h4>Top 10 performance (in bps) <span style="color:#005082">Long Biased</span></h4>
			<table class="update_brazil">
				<tr>
					<th>Ticker</th>
					<th>Last Price</th>
					<th>Exposure</th>
					<th>Contribution</th>
					<th>% Stock Chg</th>
				</tr>
				<?php
				foreach ($top10lb as $key=>$f)
				{
					echo("<tr>");

					echo("<td>{$key}</td>");

					foreach($f[0] as $jey=>$f2)
					{
						if($f2 == "NULL")
							$f2 = "";

						if(strpos($f2, "-") !== FALSE)
							$f2 = "<span style=\"color: red\">$f2</span>";
						if(strpos($f2, "(") !== FALSE)
							$f2 = "<span style=\"color: red\">$f2</span>";

						echo("<td>{$f2}</td>");
					}

					echo("</tr>");
				}
				?>
			</table>

			<h4>Bottom 10 Performance (in bps) <span style="color:#005082">Long Biased</span></h4>
			<table class="update_brazil">
				<tr>
                    <th>Ticker</th>
					<th>Last Price</th>
					<th>Exposure</th>
					<th>Contribution</th>
					<th>% Stock Chg</th>
				</tr>
				<?php
				foreach ($bottom10lb as $key=>$f)
				{
					echo("<tr>");

					echo("<td>{$key}</td>");

					foreach($f[0] as $jey=>$f2)
					{
						if($f2 == "NULL")
							$f2 = "";
						
						if(strpos($f2, "-") !== FALSE)
							$f2 = "<span style=\"color: red\">$f2</span>";
						if(strpos($f2, "(") !== FALSE)
							$f2 = "<span style=\"color: red\">$f2</span>";


						echo("<td>{$f2}</td>");
					}

					echo("</tr>");
				}
				?>
			</table>
			
			<h4>Top 10 Performance (in bps) <span style="color:#806000">Long Only</span></h4>
			<table class="update_brazil">
				<tr>
                    <th>Ticker</th>
					<th>Last Price</th>
					<th>Exposure</th>
					<th>Contribution</th>
					<th>% Stock Chg</th>
				</tr>
				<?php
				foreach ($top10lo as $key=>$f)
				{
					echo("<tr>");

					echo("<td>{$key}</td>");

					foreach($f[0] as $jey=>$f2)
					{
						if($f2 == "NULL")
							$f2 = "";

						if(strpos($f2, "-") !== FALSE)
							$f2 = "<span style=\"color: red\">$f2</span>";
						if(strpos($f2, "(") !== FALSE)
							$f2 = "<span style=\"color: red\">$f2</span>";

						echo("<td>{$f2}</td>");
					}

					echo("</tr>");
				}
				?>
			</table>
			
			<h4>Bottom 10 Performance (in bps) <span style="color:#806000">Long Only</span></h4>
			<table class="update_brazil">
				<tr>
                    <th>Ticker</th>
					<th>Last Price</th>
					<th>Exposure</th>
					<th>Contribution</th>
					<th>% Stock Chg</th>
				</tr>
				<?php
				foreach ($bottom10lo as $key=>$f)
				{
					echo("<tr>");

					echo("<td>{$key}</td>");

					foreach($f[0] as $jey=>$f2)
					{
						if($f2 == "NULL")
							$f2 = "";

						if(strpos($f2, "-") !== FALSE)
							$f2 = "<span style=\"color: red\">$f2</span>";
						if(strpos($f2, "(") !== FALSE)
							$f2 = "<span style=\"color: red\">$f2</span>";

						echo("<td>{$f2}</td>");
					}

					echo("</tr>");
				}
				?>
			</table>

			<h4>Top 10 Performance (in bps) <span style="color:#375623">Valor Relativo</span></h4>
			<table class="update_brazil">
				<tr>
                    <th>Ticker</th>
					<th>Last Price</th>
					<th>Exposure</th>
					<th>Contribution</th>
					<th>% Stock Chg</th>
				</tr>
				<?php
				foreach ($top10vr as $key=>$f)
				{
					echo("<tr>");

					echo("<td>{$key}</td>");

					foreach($f[0] as $jey=>$f2)
					{
						if($f2 == "NULL")
							$f2 = "";

						if(strpos($f2, "-") !== FALSE)
							$f2 = "<span style=\"color: red\">$f2</span>";
						if(strpos($f2, "(") !== FALSE)
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
