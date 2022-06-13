<?php
date_default_timezone_set('America/Sao_Paulo');
include "php/conexao.php";
#include "php/insere.php";
include "php/carregaDados.php";
?>


<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Leitura de Dados - Raspberry</title>

	<link rel="stylesheet" href="css/bootstrap/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
	<script type="text/javascript" src="js/script.js"></script>
	<script type="text/javascript" src="js/loader.js"></script>

	<!-- INICIO GRAFICO DE MEMORIA -->
	<script type="text/javascript" defer>
		google.charts.load("current", {
			packages: ["corechart"]
		});
		google.charts.setOnLoadCallback(drawChart);

		function drawChart() {
			var data = google.visualization.arrayToDataTable([
				['Memory', 'GB'],
				['Memória Utilizada', <?= ($result[0]['memTotal'] - $result[0]['memLivre']) ?>],
				['Memória Livre', <?= $result[0]['memLivre'] ?>]
			]);

			var options = {
				title: 'Memória do Sistema',
				titleTextStyle: {
					color: 'white',
					fontSize: 18
				},
				pieHole: 0.4,
				pieSliceTextStyle: {
					color: 'white'
				},
				backgroundColor: '#444C54',
				colors: ['#DC3545', '#007BFF'],
				legend: {
					position: 'center',
					textStyle: {
						color: 'white',
						fontSize: 16
					},
					alignment: 'center',
				}
			};

			var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
			chart.draw(data, options);
		}
	</script>
	<!-- FIM GRAFICO DE MEMORIA -->

	<!-- INICIO MEDIDOR DE TEMPERATURA -->
	<script type="text/javascript">
		google.charts.load('current', {
			'packages': ['gauge']
		});
		google.charts.setOnLoadCallback(drawChart);

		function drawChart() {

			var data = google.visualization.arrayToDataTable([
				['Label', 'Value'],
				['°C', <?= $result[0]['temp'] ?>]
			]);

			var options = {
				redFrom: 75,
				redTo: 85,
				yellowFrom: 65,
				yellowTo: 75,
				minorTicks: 5,
				max: 85
			};

			var chart = new google.visualization.Gauge(document.getElementById('medidorTemperatura'));

			chart.draw(data, options);
		}
	</script>
	<!-- FIM MEDIDOR DE TEMPERATURA -->

</head>

<body class="text-white">
	<header class="bg-info">Projeto com Raspberry Pi - Leitura, armazenamento e consulta de algumas informações</header>

	<div class="linha1" name="grid2Coluna">

		<div class="coluna1">

			<div class="divideCol1" style="position: relative;">
				<div class="estiloTemp">Temperatura Atual</div>
				<div id="medidorTemperatura" class="estiloGauge"></div>
			</div>
			<div class="divideCol1">
				<div id="donutchart" style="width: 100%;"></div>
			</div>

		</div>
		<div class="coluna2">

			<div class="divideCol2">
				<div><?php include "relogio.html"; ?></div>
				<div class="estiloLeitura">
					<?php $ultLeitura = new DateTime($result[0]['dia'] . $result[0]['hora']); ?>
					Última Checagem:<br><?= $ultLeitura->format('d/m/Y H:i:s') ?>
				</div>
			</div>
			<div class="divideCol2">
				<div><?php include "relogio2.html"; ?></div>
				<div class="estiloLeitura">
					<?php $proxLeitura = new DateTime($result[0]['dia'] . $result[0]['hora']); ?>
					Próxima Checagem:<br><?= $proxLeitura->modify('+5 minute')->format('d/m/Y H:i:s'); ?>
				</div>
			</div>
			<div class="botao">
				<form method="POST" action="javascript:void(0)" id="checaAgora">
					<div id="btnSalva">
						<button type="submit" class="btn btn-success">Checar Agora</button>
					</div>
					<div>
						<div id="horario">Hora do Sistema: --:--:--</div>
					</div>
				</form>
			</div>

		</div>

	</div>

	<div class="linha2" name="grid1Coluna">

		<table class="table table-sm table-dark text-center">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Temperatura</th>
					<th scope="col">Memória Disponível</th>
					<th scope="col">Memória Total</th>
					<th scope="col">Hora</th>
					<th scope="col">Data</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($result as $dados) : ?>
					<?php $data = new DateTime($dados['dia']); ?>

					<?php if (round($dados['memLivre'], 2) < 1 && $dados['temp'] >= 75) : ?>
						<tr class="bg-white">
					<?php elseif (round($dados['memLivre'], 2) < 1) : ?>
						<tr class="bg-danger">
					<?php elseif ($dados['temp'] >= 75) : ?>
						<tr class="bg-warning text-dark">
					<?php else : ?>
						<tr style="background:#444C54 !important;">
					<?php endif ?>
							<th scope="row"><?= $dados['id'] ?></th>
							<td><?= $dados['temp'] ?> °C</td>
							<td><?= round($dados['memLivre'], 2) ?> GB</td>
							<td><?= round($dados['memTotal'], 2) ?> GB</td>
							<td><?= $dados['hora'] ?></td>
							<td><?= $data->format('d/m/Y') ?></td>
						</tr>
					<?php endforeach; ?>
			</tbody>
		</table>

	</div>
	<!-- INICIO RELOGIO -->
	<script type="text/javascript">
		//setInterval(setDate, 1000);

		const hour = document.querySelector('[date-hour-hand]');
		const min = document.querySelector('[date-minute-hand]');
		const sec = document.querySelector('[date-second-hand]');

		var segundos = <?= $ultLeitura->format('s') ?>;
		var minutos = <?= $ultLeitura->format('i') ?>;
		var horas = <?= $ultLeitura->format('h') ?>;

		const hour2 = document.querySelector('[date-hour-hand2]');
		const min2 = document.querySelector('[date-minute-hand2]');
		const sec2 = document.querySelector('[date-second-hand2]');

		var segundos2 = <?= $proxLeitura->format('s') ?>;
		var minutos2 = <?= $proxLeitura->format('i') ?>;
		var horas2 = <?= $proxLeitura->format('h') ?>;

		function setDate() {
			const seconds = segundos / 60;
			const minutes = (seconds + minutos) / 60;
			const hours = (minutes + horas) / 12;

			setRotation(sec, seconds);
			setRotation(min, minutes);
			setRotation(hour, hours);

			const seconds2 = segundos2 / 60;
			const minutes2 = (seconds2 + minutos2) / 60;
			const hours2 = (minutes2 + horas2) / 12;

			setRotation(sec2, seconds2);
			setRotation(min2, minutes2);
			setRotation(hour2, hours2);
		}

		function setRotation(element, rotationRatio) {
			element.style.setProperty('--rotation', rotationRatio * 360);
		}

		setDate()
	</script>
	<!-- FIM RELOGIO -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script type="text/javascript">
		$('#checaAgora').on('submit', function() {
			$.ajax({
				method: 'POST',
				data: null,
				url: 'php/insere.php',
				success: function() {
					alert('Atualização Realizada');
					document.location.reload();
				}
			})
		})
	</script>
</body>
<footer class="fixar">

	<div class="row" style="font-size: 18px;">
		<div class="col"><a href="https://www.linkedin.com/in/viniciuspessan/" target=_blank>Vinícius Pessan - clique para saber mais</a></div>
		<div class="col text-right">Projeto com Raspberry Pi</div>
	</div>

</footer>

</html>