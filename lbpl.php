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

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<!-- reference your copy Font Awesome here (from our CDN or by hosting yourself) -->
	 <link href="/your-path-to-fontawesome/css/fontawesome.css" rel="stylesheet">
	 <link href="/your-path-to-fontawesome/css/brands.css" rel="stylesheet">
	 <link href="/your-path-to-fontawesome/css/solid.css" rel="stylesheet">

		<!-- include Google's AJAX API loader -->
		<script src="http://www.google.com/jsapi"></script>
		<!-- load JQuery and UI from Google (need to use UI to animate colors) -->
		<script type="text/javascript">
		google.load("jqueryui", "1.5.2");
		</script>


		<title>Portfolio - STAR [DEV]</title>

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


		<script type="text/javascript">

			$(document).ready(function () {

					$(".uptoup").stop().animate({backgroundColor:'#19d13b', color: '#ffffff'}, 500);
					setTimeout(function(){
					  $(".uptoup").stop().animate({backgroundColor:'#ffffff', color: '#19d13b'}, 3000);
					}, 3000);

					$(".uptodown").stop().animate({backgroundColor:'#19d13b', color: '#ffffff'}, 500);
					setTimeout(function(){
					  $(".uptodown").stop().animate({backgroundColor:'#ffffff', color: '#fa3939'}, 3000);
					}, 3000);


					$(".downtodown").stop().animate({backgroundColor:'#fa3939', color: '#ffffff'}, 500);
					setTimeout(function(){
					  $(".downtodown").stop().animate({backgroundColor:'#ffffff', color: '#fa3939'}, 3000);
					}, 3000);

					$(".downtoup").stop().animate({backgroundColor:'#fa3939', color: '#ffffff'}, 500);
					setTimeout(function(){
					  $(".downtoup").stop().animate({backgroundColor:'#ffffff', color: '#19d13b'}, 3000);
					}, 3000);


					$(".upvalue").stop().animate({backgroundColor:'#19d13b', color: '#ffffff'}, 500);
					setTimeout(function(){
					  $(".upvalue").stop().animate({backgroundColor:'#ffffff', color: '#000000'}, 3000);
					}, 3000);

					$(".downvalue").stop().animate({backgroundColor:'#fa3939', color: '#ffffff'}, 500);
					setTimeout(function(){
					  $(".downvalue").stop().animate({backgroundColor:'#ffffff', color: '#000000'}, 3000);
					}, 3000);

					$(".upway").stop().animate({backgroundColor:'#ffffff', color: '#19d13b'}, 100);

					$(".downway").stop().animate({backgroundColor:'#ffffff', color: '#fa3939'}, 100);

       });


		</script>

		<section class="section_update_brazil">
			<h1>Portfolio</h1>

			<?php

			$file = "json/lbpl.json";


			$jsonContents = file_get_contents($file);

			$modified = date("d/m/Y G:i:s", filemtime($file));

			$json = json_decode($jsonContents, true);

			if(empty($json))
			{
				sleep(5);
				$jsonContents = file_get_contents($file);

				$modified = date("d/m/Y G:i:s", filemtime($file));
				$json = json_decode($jsonContents, true);
				if(empty($json))
				{
					sleep(2);
					$jsonContents = file_get_contents($file);

					$modified = date("d/m/Y G:i:s", filemtime($file));
					$json = json_decode($jsonContents, true);
					if(empty($json))
					{
						echo("<div class=\"message message-error\">O arquivo não foi atualizado, favor clicar no botão \"Start Send Results\" na planilha Trading Brazil (Bloomberg).</div>");
						die();
					}
				}
			}
			$funds = $json['Portfolio'][0];
			?>

			<p>Last update: <?php echo($modified); ?> &nbsp &nbsp <svg style="height:20px; color:#d1b06f;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="sync-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-sync-alt fa-w-16 fa-spin fa-lg"><path fill="currentColor" d="M370.72 133.28C339.458 104.008 298.888 87.962 255.848 88c-77.458.068-144.328 53.178-162.791 126.85-1.344 5.363-6.122 9.15-11.651 9.15H24.103c-7.498 0-13.194-6.807-11.807-14.176C33.933 94.924 134.813 8 256 8c66.448 0 126.791 26.136 171.315 68.685L463.03 40.97C478.149 25.851 504 36.559 504 57.941V192c0 13.255-10.745 24-24 24H345.941c-21.382 0-32.09-25.851-16.971-40.971l41.75-41.749zM32 296h134.059c21.382 0 32.09 25.851 16.971 40.971l-41.75 41.75c31.262 29.273 71.835 45.319 114.876 45.28 77.418-.07 144.315-53.144 162.787-126.849 1.344-5.363 6.122-9.15 11.651-9.15h57.304c7.498 0 13.194 6.807 11.807 14.176C478.067 417.076 377.187 504 256 504c-66.448 0-126.791-26.136-171.315-68.685L48.97 471.03C33.851 486.149 8 475.441 8 454.059V320c0-13.255 10.745-24 24-24z" class="">
			</path>
			</svg></p>

			<table class="update_brazil">
				<tr>
					<th>Ticker</th>
					<th>&nbsp</th>
					<th>Price</th>
					<th>%Chg</th>
					<th>Exp LB</th>
					<th>Exp LO</th>
					<th>Ctrb LB</th>
					<th>Ctrb LO</th>

				</tr>
				<?php
				//FORMATS
				$percentager = new NumberFormatter('en_US', NumberFormatter::PERCENT);
				$percentager->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 2);
				$fDecimal = new NumberFormatter('en_US', NumberFormatter::DECIMAL);
				$fDecimal->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 1);
				$changeValue = 0;
				$idx = "";
				$stylechg = 0;

				foreach ($funds as $key=>$f)
				{
					if(strpos($key, "Ctrb") !== FALSE)
						echo("<tr class=\"update_brazil_highlight\">");
					else
						echo("<tr>");

					echo("<td>{$key}</td>");



					foreach($f[0] as $jey=>$f2)
					{



						switch ($jey) {
							case 'VL_EXP_LB':
							case 'VL_EXP_LO':
								$f2 = $percentager->format($f2);
								if ($f2 == 0){
									$f2 = "<td >&nbsp</td>";
								}else {
									if(strpos($f2, "-") !== FALSE){
										//$stylechg = -1;
										switch ($changeValue) {
											case 1:
												$f2 = "<td class=\"uptodown\">$f2</td>";
												break;

											case 0:
												$f2 = "<td class=\"downway\">$f2</td>";
												break;

											case -1:
												$f2 = "<td class=\"downtodown\">$f2</td>";
												break;
										}
									}else {
										//$stylechg = 1;
										if ($f2 == 0){
											$f2 = "<td style=\"color:#b3b3b3;\" >$f2</td>";
										}else {
											switch ($changeValue) {
												case 1:
													$f2 = "<td class=\"uptoup\">$f2</td>";
													break;

												case 0:
													$f2 = "<td class=\"upway\">$f2</td>";
													break;

												case -1:
													$f2 = "<td class=\"downtoup\">$f2</td>";
													break;
											}
										}
									}
								}

								break;

							case 'VL_CHANGE':
								$f2 = $percentager->format($f2);
								if(strpos($f2, "-") !== FALSE){
									//$stylechg = -1;
									switch ($changeValue) {
										case 1:
											$f2 = "<td class=\"uptodown\">$f2</td>";
											break;

										case 0:
											$f2 = "<td class=\"downway\">$f2</td>";
											break;

										case -1:
											$f2 = "<td class=\"downtodown\">$f2</td>";
											break;
									}
								}else {
									//$stylechg = 1;
									if ($f2 == 0){
										$f2 = "<td style=\"color:#b3b3b3;\" >$f2</td>";
									}else {
										switch ($changeValue) {
											case 1:
												$f2 = "<td class=\"uptoup\">$f2</td>";
												break;

											case 0:
												$f2 = "<td class=\"upway\">$f2</td>";
												break;

											case -1:
												$f2 = "<td class=\"downtoup\">$f2</td>";
												break;
										}
									}
								}

								break;

								case 'VL_CTRB_LO':
								case 'VL_CTRB_LB':
									$f2 = $fDecimal->format($f2);
									if(strpos($f2, "-") !== FALSE){
										//$stylechg = -1;
										switch ($changeValue) {
											case 1:
												$f2 = "<td class=\"uptodown\">$f2</td>";
												break;

											case 0:
												$f2 = "<td class=\"downway\">$f2</td>";
												break;

											case -1:
												$f2 = "<td class=\"downtodown\">$f2</td>";
												break;
										}
									}else {
										//$stylechg = 1;
										if ($f2 == 0){
											$f2 = "<td>&nbsp</td>";
										}else{
											switch ($changeValue) {
												case 1:
													$f2 = "<td class=\"uptoup\">$f2</td>";
													break;

												case 0:
													$f2 = "<td class=\"upway\">$f2</td>";
													break;

												case -1:
													$f2 = "<td class=\"downtoup\">$f2</td>";
													break;
											}
										}
									}

									break;

							case 'Movimento':
									$changeValue = $f2;
									$f2 = "<td></td>";
								break;

							default:
								$f2 = $fDecimal->format($f2);
								$f2 = "<td >$f2</td>";
								break;
						}


						//if($f2 == "NULL" || $f2 == "0")
							//$f2 =  "<td>&nbsp</td>";
						/*if ($jey == 'VL_CHANGE'){
							if(strpos($f2, "-") !== FALSE){
								$stylechg = -1;
							}else {
								$stylechg = 1;
							}

							if ($changeValue == -1)
									$f2 = "<td class=\"downvalue\" >$f2</td>";
									//$f2 = "<td ><span style=\"background-color: red\">$f2</span></td>";
							if ($changeValue == 1)
									$f2 = "<td class=\"upvalue\"  >$f2</td>";

						}*/


						/*if ($jey == 'Movimento'){
							$f2 = "<td></td>";

						}elseif ($jey == 'VL_CHANGE'){
							if(strpos($f2, "-") !== FALSE){
								$stylechg = -1;
							}else {
								$stylechg = 1;
							}

							if ($changeValue == -1 && $stylechg = -1)
									$f2 = "<td class=\"downtodown\">$f2</td>";

							if ($changeValue == -1 && $stylechg = 1)
									$f2 = "<td class=\"downtoup\">$f2</td>";

							if ($changeValue == 1 && $stylechg = 1)
									$f2 = "<td class=\"uptoup\">$f2</td>";

							if ($changeValue == 1 && $stylechg = -1)
									$f2 = "<td class=\"uptodown\">$f2</td>";

							if ($changeValue == 0 && $stylechg = 1)
									$f2 = "<td class=\"upway\">$f2</td>";

							if ($changeValue == 0 && $stylechg = -1)
									$f2 = "<td class=\"downway\">$f2</td>";

						}else{

							if ($changeValue == -1)
									$f2 = "<td class=\"downvalue\">$f2</td>";
									//$f2 = "<td ><span style=\"background-color: red\">$f2</span></td>";
							if ($changeValue == 1)
									$f2 = "<td class=\"upvalue\">$f2</td>";

							if ($changeValue == 0)
									$f2 = "<td >$f2</td>";
						}*/



						echo("{$f2}");
					}

					echo("</tr>");


				}
				?>
			</table>

		</section>
    </body>
</html>

<?php
ob_end_flush();
?>
