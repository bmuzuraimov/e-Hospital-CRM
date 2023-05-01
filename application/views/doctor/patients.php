<?php
function getAge($dateofbirth)
{
$today = date("Y-m-d");
$diff  = date_diff(date_create($dateofbirth), date_create($today));
$diff  = $diff->format('%y');
return $diff;
}
?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" conternt="ie=edge">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link href="https://fonts.googleapis.com/css2?family=Lora:wght@500&family=Merriweather:wght@300&family=Sansita+Swashed:wght@300;400&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="/public/styles/style.css">
		<link rel="stylesheet" href="/public/styles/doctor.css">
		<link rel="icon" href="/public/images/icons/icon_doctor.ico" type="image/icon">
		<title>Пациенты</title>
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
					<a href="/doctor"><?=$username['fname']." ".$username['lname']?><i class="fa fa-chevron-down"></i></a>
					<?php } else { ?>
					<a href="/doctor/editprofile">Настройка профиля<i class="fa fa-chevron-down"></i></a>
					<?php }
					?>
					<ul>
						<li><a href="/doctor/logout" class="logout">Выйти</a></li>
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
		<div class="doctor-wrapper">
			<!--Left Sidebar-->
			<div class="left-sidebar">
				<?php include('application/include/doctor_sidebar.html'); ?>
			</div>
			<!--//Left Sidebar-->
			<!--doctor Content-->
			<div class="doctor-content">
				<div class="content">
					<div class="button-group">
						<a href="/doctor/addp" class="btn  btn-big">Добавить пациента</a>
					</div>
					<h2 class="page-title">Запись пациентов</h2>
					<div class="tab" style="grid-template-columns: auto auto auto auto;">
						<a href="/doctor/patients?patients=all">Все</a>
						<a href="/doctor/patients?patients=hospitalized">Госпитализированные</a>
						<a href="/doctor/patients?patients=waiting">В ожидании</a>
						<a href="/doctor/patients?patients=recovered">Вылеченные</a>
					</div>
					<div>
		          		<input type="text" name="patient_search" class="text-input" placeholder="Найти..." />
		          	</div>
					<table>
						<tr>
							<th>ID</th>
							<th>Имя</th>
							<th>Фамилия</th>
							<th>Возраст</th>
							<th>Риск</th>
							<th>Просмотр</th>
						</tr>
						<?php
						foreach ($patients as $patient):
						?>
						<tr>
							<td><?=$patient['id'];?></td>
							<td><?=$patient['fname'];?></td>
							<td><?=$patient['lname'];?></td>
							<td><?=getAge($patient['age']);?></td>
							<td><?=$patient['risk'];?></td>
							<td><?php
								if (is_null($patient['illness_data'])) {?>
								<a href="/doctor/diagnostic?patientid=<?=$patient['id'];?>" class="delete">Диагноз</a>
								<?php
								} else {?>
								<a href="/doctor/viewp?patientid=<?=$patient['id'];?>" class="publish">Подробнее</a>
							</form>
							<?php }?>
						</td>
					</tr>
					<?php
					endforeach;
					?>
				</table>
			</div>
		</div>
		<!--//doctor Content-->
	</div>
	<!-- //Page Wrapper -->
	<!-- JQuery -->
	<script type="text/javascript" src="/public/scripts/jquery.js"></script>
	<!-- Custom -->
	<script src="/public/scripts/scripts.js"></script>
</body>
</html>