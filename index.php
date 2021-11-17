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

		<title>Daily Reports - STAR [DEV]</title>

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
			<h1>Daily Reports</h1>

			<h2>Brazil Funds</h2>
			<div class="image-documents-grid">
				<a href="../pdf.php?file=Performance_Fundos.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/funds_performance.png">
						<p>Funds Perfomance</p>
					</div>
				</a>
				<a href="../pdf.php?file=Consolidado%20Brasil.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/brazil_funds2.png">
						<p>Portfolio Strategy</p>
					</div>
				</a>
				<a href="../pdf.php?file=RiskFactor_Report.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/risk_factor.png">
						<p>Risk Factor</p>
					</div>
				</a>
				<a href="../pdf.php?file=RiskFactor_D_Report.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/risk_factor.png">
						<p>Risk Factor Detalhado</p>
					</div>
				</a>
				<a href="../pdf.php?file=Funds_Peer_Comparison.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/funds_comparison.png">
						<p>STK x Peers Comparison</p>
					</div>
				</a>
				<a href="../pdf.php?file=Turnover.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/turnover.png">
						<p>Turnover Estratégia Long Brazil</p>
					</div>
				</a>
			</div>

			<h2>STK Long Biased</h2>
			<div class="image-documents-grid">
				<a href="../pdf.php?file=STK%20Long%20Biased%20Master%20FIA%20Detalhado_Longs.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/long_biased_long_book.png">
						<p>Portfolio Breakdown</p>
					</div>
				</a>
				<!-- <a href="../pdf.php?file=STK%20Long%20Biased%20Master%20FIA%20Optimal%20Long%20Book.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/long_only_optimal_positions.png">
						<p>Optimal Positions</p>
					</div>
				</a> -->
				<a href="../pdf.php?file=STK%20Long%20Biased%20Master%20FIA%20Optimal%20Long%20Book_test_short.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/long_only_optimal_positions.png">
						<p>Optimal Positions</p>
					</div>
				</a>
				<a href="../pdf.php?file=STK%20Long%20Biased%20Master%20FIA%20Optimal%20Long%20Book%20-%20Previa.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/long_only_optimal_positions.png">
						<p>Optimal Positions - Real Time</p>
					</div>
				</a>
				<a href="../pdf.php?file=attribution_lb.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/perf_attrb.png">
						<p>Performance Attribution ITD</p>
					</div>
				</a>
				<a href="../pdf.php?file=sa_lb.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/sa_lb.png">
						<p>Long Brazil Sector Attribution ITD</p>
					</div>
				</a>
				<a href="../pdf.php?file=STK%20Long%20Biased%20Master%20FIA%20Contribution.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/long_biased_attribution.png">
						<p>Attribution</p>
					</div>
				</a>
				<a href="../pdf.php?file=Pair_Trade_Report.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/pairtrades_gerencial.png">
						<p>Valor Relativo Gerencial</p>
					</div>
				</a>
				<a href="../pdf.php?file=Risk_Report_LB.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/risk_report.png">
						<p>Risk Report</p>
					</div>
				</a>
				<a href="../pdf.php?file=Options_STK%20Long%20Biased%20Master%20FIA.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/long_biased_options_book.png">
						<p>Options Book</p>
					</div>
				</a>
				<a href="../pdf.php?file=Relatorio_Rebalanceamento.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/rb.png">
						<p>Rebalanceamento</p>
					</div>
				</a>
				<a href="../pdf.php?file=perf_arb.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/perf_arb.png">
						<p>Performance Arbitragens</p>
					</div>
				</a>

			</div>
			<h2>STK Long Biased Prev Icatu Qualificado FIFE FIA</h2>
			<div class="image-documents-grid">
				<a href="../pdf.php?file=STK%20Long%20Biased%20Prev%20Icatu%20Qualificado%20FIFE%20FIA%20Detalhado_Longs.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/long_biased_long_book.png">
						<p>Portfolio Breakdown</p>
					</div>
				</a>
				<a href="../pdf.php?file=STK%20Long%20Biased%20Prev%20Icatu%20Qualificado%20FIFE%20FIA%20Optimal%20Long%20Book.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/long_only_optimal_positions.png">
						<p>Optimal Positions</p>
					</div>
				</a>
				<a href="../pdf.php?file=Options_STK%20Long%20Biased%20Prev%20Icatu%20Qualificado%20FIFE%20FIA.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/long_biased_options_book.png">
						<p>Options Book</p>
					</div>
				</a>
			</div>

			<h2>STK Long Biased Master FIM</h2>
			<div class="image-documents-grid">
				<a href="../pdf.php?file=STK%20Long%20Biased%20Master%20FIM%20Detalhado_Longs.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/long_biased_long_book.png">
						<p>Portfolio Breakdown</p>
					</div>
				</a>
				<a href="../pdf.php?file=STK%20Long%20Biased%20Master%20FIM%20Contribution.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/long_biased_attribution.png">
						<p>Attribution</p>
					</div>
				</a>
				<a href="../pdf.php?file=STK%20Long%20Biased%20Master%20FIM%20Optimal%20Long%20Book_test_short.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/long_only_optimal_positions.png">
						<p>Optimal Positions</p>
					</div>
				</a>
				<a href="../pdf.php?file=Options_STK%20Long%20Biased%20Master%20FIM.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/long_biased_options_book.png">
						<p>Options Book</p>
					</div>
				</a>
			</div>

			<h2>STK Long Only</h2>
			<div class="image-documents-grid">
				<a href="../pdf.php?file=STK%20Long%20Only%20Institucional%20FIA%20Contribution.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/long_biased_attribution.png">
						<p>Attribution</p>
					</div>
				</a>
				<a href="../pdf.php?file=STK%20Long%20Only%20Institucional%20FIA%20Detalhado_Longs.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/long_biased_long_book.png">
						<p>Portfolio Breakdown</p>
					</div>
				</a>
				<a href="../pdf.php?file=STK%20Long%20Only%20Institucional%20FIA%20Optimal%20Long%20Book.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/long_only_optimal_positions.png">
						<p>Optimal Positions</p>
					</div>
				</a>
				<a href="../pdf.php?file=STK%20Long%20Only%20Institucional%20FIA%20Optimal%20Long%20Book%20-%20Previa.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/long_only_optimal_positions.png">
						<p>Optimal Positions - Real Time</p>
					</div>
				</a>
				<a href="../pdf.php?file=Risk_Report_LO.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/risk_report.png">
						<p>Risk Report</p>
					</div>
				</a>
				<a href="../pdf.php?file=STK%20Long%20Only%20Institucional%20FIA%20Results%20Optimal.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/long_only_optimal_results.png">
						<p>Optimal Results</p>
					</div>
				</a>
				<a href="../pdf.php?file=lo_att_2020.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/lo_att.png">
						<p>Performance Attribution 2020</p>
					</div>
				</a>
				<a href="../pdf.php?file=lo_att_2019.pdf" target="_blank">
					<div class="image-document">
						<img src="img/documents/lo_att.png">
						<p>Performance Attribution 2019</p>
					</div>
				</a>
			</div>
		</section>
    </body>
</html>
<?php
ob_end_flush();
?>
