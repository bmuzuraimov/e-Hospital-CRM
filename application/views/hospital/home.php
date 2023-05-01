<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" conternt="ie=edge">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link href="https://fonts.googleapis.com/css2?family=Lora:wght@500&family=Merriweather:wght@300&family=Sansita+Swashed:wght@300;400&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="/public/styles/style.css">
		<link rel="stylesheet" href="/public/styles/hospital.css">
		<link rel="stylesheet" href="/public/styles/morris.css">
		<link rel="icon" href="/public/images/icons/icon_hospital.ico" type="image/icon">
		<title>Главная страница</title>
	</head>
	<body>
		<header>
			<div class="logo">
				<h1 class="logo-text"><a href="/"><span>B</span>aiel-<span>A</span>I</a></h1>
			</div>
			<i class="fa fa-bars menu-toggle"></i>
			<ul class="nav">
				<li>
					<?php
					if ($username['fname']!=false && $username['lname']!=false) { ?>
						<a href="/hospital"><?=$username['fname']." ".$username['lname']?><i class="fa fa-chevron-down"></i></a>
					<?php } else { ?>
						<a href="/hospital/editprofile">Настройка профиля<i class="fa fa-chevron-down"></i></a>
					<?php }
					?>
					<ul>
						<li><a href="/hospital/logout" class="logout">Выйти</a></li>
					</ul>
				</li>
			</ul>
		</header>
		<!-- Analytics -->
		<div class="analytics">
			<ul class="analytics-total">
				<li class="analytics-title">За все время</li>
				<li class="total"><i class="fas fa-user-injured"></i>&nbsp;<?=$analytics['gen_total'];?></li>
				<li class="healed"><i class="fas fa-star-of-life"></i>&nbsp;<?=$analytics['gen_healed'];?></li>
				<li class="dead"><i class="fas fa-heart-broken"></i>&nbsp;<?=$analytics['gen_dead'];?></li>
				<li class="analytics-title">За сегодня</li>
				<li class="total"><i class="fas fa-user-injured"></i>&nbsp;<?=$analytics['day_total'];?></li>
				<li class="healed"><i class="fas fa-star-of-life"></i>&nbsp;<?=$analytics['day_healed'];?></li>
				<li class="dead"><i class="fas fa-heart-broken"></i>&nbsp;<?=$analytics['day_dead'];?></li>
				<li class="analytics-title">Койки</li>	
				<li class="total"><i class="fas fa-procedures"></i>&nbsp;<?=$analytics['aunits'];?>/<?=$analytics['tunits'];?></li>	
			</ul>
		</div>
		<!-- Page Wrapper -->
		<div class="hospital-wrapper">
			<!--Left Sidebar-->
			<div class="left-sidebar">
				<?php include('application/include/hospital_sidebar.html'); ?>
			</div>
			<!--//Left Sidebar-->
			<!--Hospital Content-->
			<div class="hospital-content">
				<div class="content">
					<h2 class="page-title">Панель Глав Врача Больницы</h2>
					<div class="info">
						<h3>Инструкция</h3>
						<div class="post-content">
							<p>&emsp;Use Intro.js later</p>
						</div>
					</div>
					<h2 class="page-title">Факторы Риска</h2>
					<div class="donut" id="graph" style=""></div><br><br>
					<h2 class="page-title">Рост за Последние 7 Дней</h2>
					<div class="graph" id="patient_growth" style=""></div>
				</div>
			</div>
			<!--//Hospital Content-->
		</div>
		<!-- //Page Wrapper -->
		<!-- JQuery -->
		<script type="text/javascript" src="/public/scripts/jquery.js"></script>
		<!-- Custom -->
		<script src="/public/scripts/scripts.js"></script>
		<!-- Morris -->
		<script src="/public/scripts/morris.js"></script>
		<!-- Raphael-min -->
		<script src="/public/scripts/raphael-min.js"></script>
		<script>
			Morris.Donut({
			element: 'graph',
			data: [
			{value: 35, label: 'Заболевания', formatted: '35%' },
			{value: 25, label: 'Возраст', formatted: '25%' },
			{value: 5, label: 'Пол', formatted: '5%' },
			{value: 15, label: 'Дыхание', formatted: '15%' },
			{value: 10, label: 'Температура', formatted: '10%' },
			{value: 5, label: 'Слабость', formatted: '5%' },
			],
			formatter: function (x, data) { return data.formatted; }
			});
			Morris.Area({
			element: 'patient_growth',
			redraw: true,
			behaveLikeLine: true,
			data: [
			<?php foreach ($patients_growth as $patients):
			echo "{date: '" . $patients['day'] . "', y: '" . $patients['patient'] . "'},";
			endforeach; ?>
			],
			parseTime: false,
			xkey: 'date',
			ykeys: 'y',
			labels: ['Пациентов', 'y']
			});
		</script>
	</body>
</html>