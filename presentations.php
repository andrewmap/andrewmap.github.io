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

		<title>Presentations - STAR [DEV]</title>

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
			<h1>Presentations</h1>
			<div class="image-documents-grid">
				<!-- <a href="../pdf.php?file=Relatório de Desempenho STK Long Biased _30_06_2020.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/relatorio_desempenho_long_biased.png">
						<p>Relatório de Desempenho STK Long Biased Junho 2020</p>
                        <div class="image-document-flag flag-br"></div>
					</div>
				</a>
				<a href="../pdf.php?file=Relatório de Desempenho Estratégia STK Long Only _30_06_2020.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/relatorio_estrategia_long_only.png">
						<p>Relatório de Desempenho Estratégia STK Long Only Junho 2020</p>
                        <div class="image-document-flag flag-br"></div>
					</div>
				</a>
				<a href="../pdf.php?file=Relatório de Desempenho Conquista FIC FIA _30_06_2020.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/relatorio_desempenho_conquista.png">
						<p>Relatório de Desempenho Conquista FIC FIA Junho 2020</p>
                        <div class="image-document-flag flag-br"></div>
					</div>
				</a>
				<a href="../pdf.php?file=Performance Review STK Long Biased _30_06_2020.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/relatorio_desempenho_long_biased.png">
						<p>Performance Review STK Long Biased June 2020</p>
                        <div class="image-document-flag flag-usa"></div>
					</div>
				</a>
				<a href="../pdf.php?file=Performance Review STK Long Only Strategy _30_06_2020.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/relatorio_estrategia_long_only.png">
						<p>Performance Review STK Long Only Strategy June 2020</p>
                        <div class="image-document-flag flag-usa"></div>
					</div>
				</a>
				<a href="../pdf.php?file=Performance Review Conquista FIC FIA _30_06_2020.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/relatorio_desempenho_conquista.png">
						<p>Performance Review Conquista FIC FIA June 2020</p>
                        <div class="image-document-flag flag-usa"></div>
					</div>
				</a> -->
				<a href="../pdf.php?file=STK Capital_Apresentacao_Institucional.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/stk_apresentacao.png">
						<p>Apresentação STK Capital</p>
					</div>
				</a>
				<a href="../pdf.php?file=STK Capital_Apresentacao_Institucional_Cliente.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/stk_apresentacao.png">
						<p>Apresentação STK Capital - Cliente</p>
					</div>
				</a>
				<a href="../pdf.php?file=STK Capital_Apresentacao_Institucional_LB.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/stk_apresentacao.png">
						<p>Apresentação STK Capital LB</p>
					</div>
				</a>
				<a href="../pdf.php?file=STK Capital_Apresentacao_Institucional_LO.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/stk_apresentacao.png">
						<p>Apresentação STK Capital LO</p>
                        <!-- <div class="image-document-flag flag-br"></div> -->
					</div>
				</a>
            </div>

            <h2>Lâminas</h2>
            <div class="image-documents-grid">
				<a href="../pdf.php?file=longbiased.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/lamina_long_biased.png">
						<p>STK Long Biased FIC FIA</p>
					</div>
				</a>
				<a href="../pdf.php?file=longonly.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/lamina_long_only.png">
						<p>STK Long Only FIA</p>
					</div>
				</a>
				<!-- <a href="../pdf.php?file=One.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/lamina_one.png">
						<p>STK One FIA</p>
					</div>
				</a> -->
            </div>

            <h2>Cases</h2>
            <div class="image-documents-grid">
				<a href="../pdf.php?file=Mercado_Libre.pdf" target="_blank">
                    <div class="image-document">
                        <img src="img/documents/meli.png">
                        <p>Mercado Livre</p>
                    </div>
                </a>
				<a href="../pdf.php?file=Lojas_Renner.pdf" target="_blank">
                    <div class="image-document">
                        <img src="img/documents/lojasrenner.png">
                        <p>Lojas Renner</p>
                    </div>
                </a>
				<a href="../pdf.php?file=PayPal.pdf" target="_blank">
                    <div class="image-document">
                        <img src="img/documents/paypal.png">
                        <p>PayPal</p>
                    </div>
                </a>
				<a href="../pdf.php?file=Natura.pdf" target="_blank">
                    <div class="image-document">
                        <img src="img/documents/natura.png">
                        <p>Natura</p>
                    </div>
                </a>
                <a href="../pdf.php?file=STBP roadshow port DRAFT.pdf" target="_blank">
                    <div class="image-document">
                        <img src="img/documents/santos_brasil.png">
                        <p>Santos Brasil</p>
                    </div>
                </a>
                <a href="../pdf.php?file=Energisa - STK Presentation.pdf" target="_blank">
                    <div class="image-document">
                        <img src="img/documents/energisa.png">
                        <p>Energisa</p>
                    </div>
				</a>
				<a href="../pdf.php?file=Hapvida.pdf" target="_blank">
                    <div class="image-document">
                        <img src="img/documents/hapvida.png">
                        <p>Hapvida</p>
                    </div>
				</a>
				<a href="../pdf.php?file=Intermedica.pdf" target="_blank">
                    <div class="image-document">
                        <img src="img/documents/Intermedica.png">
                        <p>Intermedica</p>
                    </div>
				</a>
				<a href="../pdf.php?file=Qualicorp.pdf" target="_blank">
                    <div class="image-document">
                        <img src="img/documents/qualicorp.png">
                        <p>Qualicorp</p>
                    </div>
				</a>
				<a href="../pdf.php?file=Light.pdf" target="_blank">
                    <div class="image-document">
                        <img src="img/documents/Light.png">
                        <p>Light</p>
                    </div>
				</a>
				<a href="../pdf.php?file=Petro.pdf" target="_blank">
                    <div class="image-document">
                        <img src="img/documents/petro.png">
                        <p>Petrobras</p>
                    </div>
				</a>
                <a href="../pdf.php?file=Carrefour Brasil - STK Presentation.pdf" target="_blank">
                    <div class="image-document">
                        <img src="img/documents/carrefour.png">
                        <p>Carrefour Brasil</p>
                    </div>
                </a>
                <a href="../pdf.php?file=Macro Database Brazil.pdf" target="_blank">
                    <div class="image-document">
                        <img src="img/documents/macro_database_brazil.png">
                        <p>Macro Database Brazil</p>
                    </div>
                </a>
                <a href="../pdf.php?file=Macro Database Global.pdf" target="_blank">
                    <div class="image-document">
                        <img src="img/documents/macro_database_global.png">
                        <p>Macro Database Global</p>
                    </div>
                </a>
                <a href="../pdf.php?file=Technical Condition.pdf" target="_blank">
                    <div class="image-document">
                        <img src="img/documents/technical_condition.png">
                        <p>Technical Condition</p>
                    </div>
                </a>
            </div>
		</section>
    </body>
</html>
<?php
ob_end_flush();
?>
