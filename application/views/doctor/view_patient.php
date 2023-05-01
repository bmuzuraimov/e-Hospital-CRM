<?php
function getAge($dateofbirth){
	$today = date("Y-m-d");
	$diff = date_diff(date_create($dateofbirth), date_create($today));
	$diff = $diff->format('%y');
	return $diff;
}
function remaining_days($toDate){
	$today = date("Y-m-d");
	if ($toDate!=false) {
		$diff = date_diff(date_create($toDate), date_create($today));
		$diff = $diff->format('%d');
		return $diff;
	}else{
		return 7;
	}
}
function illness_name($illness){
	$illness = round($illness);
	$illness = strval($illness);
	$illnesses = ['100' => "Сердечно-сосудистые заболевания", '69' => "Диабет", '64' => "Гипертония", '60' => "Хронические заболевания дыхательных путей", '57' => "Рак", '0' => "Отсутствует"];
	if (array_key_exists($illness, $illnesses)) {
		$illness = $illnesses[$illness];
		return $illness;
	} else {
		return "Отсутствует";

	}
}
function assess_breath($breath){
	$breaths = ["20" => "0-10%", "40" => "10-20%", "60" => "20-40%", "80" => "40-60%", "100" => ">60%",];
	if (array_key_exists($breath, $breaths)) {
		return $breaths[$breath];
	} else {
		return "0%";
	}
}
function profile_img($sex, $age){
	if($sex=='male'){
		switch ($age) {
			case ($age >= 0 && $age < 12):
				return '/public/images/patient_profile/posmchild.png';
				break;
			case ($age >= 12 && $age < 50):
				return '/public/images/patient_profile/posmperson.png';
				break;
			case ($age >= 50):
				return '/public/images/patient_profile/posmold.png';
				break;
			default:
				return '/public/images/patient_profile/posmperson.png';
				break;
		}
	}else{
		switch ($age) {
			case ($age >= 0 && $age < 12):
				return '/public/images/patient_profile/posfchild.png';
				break;
			case ($age >= 12 && $age < 50):
				return '/public/images/patient_profile/posfperson.png';
				break;
			case ($age >= 50):
				return '/public/images/patient_profile/posfold.png';
				break;
			default:
				return '/public/images/patient_profile/posfperson.png';
				break;
		}
	}
}
$illness = $risk['illness'];
$age = $risk['age'];
$sex = $risk['sex'];
$breath = $risk['breath'];
$temp = $risk['temperature'];
$weakness = $risk['weakness'];
$illness *= $illnessrisk;
$age *= $agerisk;
$temp = ((($temp-36) * (100)) / (40 - 36));
$temp *= $temprisk;
$sex *= $genderrisk;
$breath *= $breathrisk;
$weakness *= $weaknessrisk;
$cond = $illness+$age+$temp+$sex+$breath+$weakness;
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
		<link rel="stylesheet" href="/public/styles/profile.css">
		<link rel="icon" href="/public/images/icons/icon_doctor.ico" type="image/icon">
		<title>Просмотр пациента</title>
		<style type="text/css">
			.ldBar-label {
				color: #555a5e;
				font-family: 'varela round';
				text-align: center;
				font-size: 1.3em;
				font-weight: 900;
			}
		</style>
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
					<h3 class="page-title">Статус: <?=$status;?></h3>
					<div class="wrapper">
						<div class="doctor-patient-left">
							<img src="<?=profile_img($patient['sex'], getAge($patient['age']));?>" alt="profile picture">
							<h4 class="page-title"><?=$patient['fname'] . " " . $patient['lname'];?></h4>
							<a href="#">ИНН: <?=$patient['passport'];?></a>
							<h5 class="publish"><a href="tel:<?=$patient['emcontact']?>"><?=$patient['emcontact']?></a></h5>
						</div>
						<div class="right">
							<div class="info">
								<form id="edit-doctor" method="get" action="/doctor/editpatient">
									<input type="hidden" name="patientid" value=<?=$patient["id"];?>>
									<button type="submit">Редактировать</button>
								</form><br><br>
								<h3>Инфо</h3>
								<div class="info-data">
									<div class="data">
										<h4>Дата рождения</h4>
										<p><?=$patient['age']?></p>
									</div>
									<div class="data">
										<h4>Поражение легких</h4>
										<p><?=assess_breath($risk['breath'])?></p>
									</div>
									<div class="data">
										<h4>Общее Состояние</h4>
										<div id="myBar">
										</div>
									</div>
								</div>
							</div>
							<div class="extra-info">
								<h3></h3>
								<div class="extra-info-data">
									<div class="data">
										<h4>Хронические заболевания</h4>
										<p><?=illness_name($risk['illness'])?></p>
									</div>
									<div class="data" style="width: 100%;">
										<h4>Симптомы</h4>
										<p><?=$patient['symptoms']?></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="treatmentplan">
						<h1 class="page-title"><i class="fas fa-check" style="color: blue;"></i>&nbsp;Лечение</h1>
						<div class="timeline-area">
							<?php
							$treatment_history_day = 1;
							if ($my_treatment_days != false) {
							for ($i = 0; $i < $my_treatment_days[0]; $i++) {
							$next_date = $i + 1;?>
							<div class="treatment ai">
								<h3 class="page-title"><?="День $treatment_history_day"?></h3>
								<div class="day-treatment">
									<div class="medicine">
										<input type="text" class="text-input" name="medicine" value="<?=$my_treatments[$i]['name']?>" disabled>
										<input type="text" class="text-input" name="medicine" value="<?=$my_treatments[$i]['dose'] . " мг"?>" disabled>
										<select name="time" class="text-input" disabled>
										<?php
										if ($my_treatments[$i]['time'] > '05:00' && $my_treatments[$i]['time'] < '12:00') {
										?>
											<option value="morning" selected>Утром</option>
										<?php } else if ($my_treatments[$i]['time'] >= '12:00' && $my_treatments[$i]['time'] < '18:00') {?>
											<option value="afternoon" selected>Днем</option>
										<?php } else {?>
											<option value="evening" selected>Вечером</option>
										<?php
										}
										?>
										</select>
									</div>
									<?php
									while ($my_treatments[$i]['date'] == $my_treatments[$next_date]['date']) {
									?>
									<div class="<?=$my_treatments[$next_date]['purpose']?>">
										<input type="text" class="text-input" name="medicine" value="<?=$my_treatments[$next_date]['name']?>" disabled>
										<input type="text" class="text-input" name="medicine" value="<?=$my_treatments[$next_date]['dose'] . " мг"?>" disabled>
										<select name="time" class="text-input" disabled>
										<?php
										if ($my_treatments[$next_date]['time'] > '05:00' && $my_treatments[$next_date]['time'] < '12:00') {
										?>
											<option value="morning" selected>Утром</option>
										<?php } else if ($my_treatments[$next_date]['time'] >= '12:00' && $my_treatments[$next_date]['time'] < '18:00') {?>
											<option value="afternoon" selected>Днем</option>
										<?php } else {?>
											<option value="evening" selected>Вечером</option>
										<?php }?>
										</select>
									</div>
									<?php
									$next_date++;
									}
									?>
								</div>
							</div>
							<?php
							$treatment_history_day++;
							}
							}
							?>
							<?php
							$plan_treatment_day = 1;
							if ($plan_treatment_days != false) {
							$plan_medicine_num = 0;
							for ($i = 0; $i < $plan_treatment_days; $i++) {
							$next_plan_medicine_num = $plan_medicine_num + 1;
							?>
							<div class="treatment plan">
								<h3 class="page-title"><?="День $plan_treatment_day"?></h3>
								<div class="day-treatment" id='<?="day-".$plan_treatment_day?>'>
									<div class="medicine-block" id='<?="medicine-block-".$plan_medicine_num?>'>
										<input type="text" class="text-input" name="medicine_<?=$plan_medicine_num;?>" value="<?=$plan_treatments[$plan_medicine_num]['name']?>" disabled>
										<input type="text" class="text-input" name='<?="dose_<?=$plan_medicine_num;?>".$plan_medicine_num?>' value="<?=$plan_treatments[$plan_medicine_num]['dose'] . " мг"?>" disabled>
										<select name="time_<?=$plan_medicine_num;?>" class="text-input" disabled>
										<?php
										if ($plan_treatments[$plan_medicine_num]['time'] > 5 && $plan_treatments[$plan_medicine_num]['time'] < 12) {
										?>
											<option value="morning" selected>Утром</option>
											<option value="afternoon">Днем</option>
											<option value="evening">Вечером</option>
										<?php } else if ($plan_treatments[$plan_medicine_num]['time'] >= 12 && $plan_treatments[$plan_medicine_num]['time'] < 18) { ?>
											<option value="morning">Утром</option>
											<option value="afternoon" selected>Днем</option>
											<option value="evening">Вечером</option>
										<?php } elseif ($plan_treatments[$plan_medicine_num]['time'] >= 18 && $plan_treatments[$plan_medicine_num]['time'] < 24) { ?>
											<option value="morning">Утром</option>
											<option value="afternoon">Днем</option>
											<option value="evening" selected>Вечером</option>
										<?php } else {?>
											<option value="morning">Утром</option>
											<option value="afternoon" selected>Днем</option>
											<option value="evening">Вечером</option>
										<?php } ?>
										</select>
										<input type="hidden" name="day_<?=$plan_medicine_num;?>" value="<?=$plan_treatment_day?>">
									</div>
									<?php
									while ($next_plan_medicine_num<count($plan_treatments) && $plan_treatments[$plan_medicine_num]['day'] == $plan_treatments[$next_plan_medicine_num]['day']) {
									$plan_medicine_num++;
									?>
									<div class="medicine-block" id='<?="medicine-block-".$plan_medicine_num?>'>
										<input type="text" class="text-input" name="medicine_<?=$plan_medicine_num;?>" value="<?=$plan_treatments[$next_plan_medicine_num]['name']?>" disabled>
										<input type="text" class="text-input" name="dose_<?=$plan_medicine_num;?>" value="<?=$plan_treatments[$next_plan_medicine_num]['dose'] . " мг"?>" disabled>
										<select name="time_<?=$plan_medicine_num;?>" class="text-input" disabled>
										<?php
										if ($plan_treatments[$next_plan_medicine_num]['time'] > 5 && $plan_treatments[$next_plan_medicine_num]['time'] < 12) {
										?>
											<option value="morning" selected>Утром</option>
											<option value="afternoon">Днем</option>
											<option value="evening">Вечером</option>
										<?php } else if ($plan_treatments[$next_plan_medicine_num]['time'] >= 12 && $plan_treatments[$next_plan_medicine_num]['time'] < 18) { ?>
											<option value="morning">Утром</option>
											<option value="afternoon" selected>Днем</option>
											<option value="evening">Вечером</option>
										<?php } elseif ($plan_treatments[$next_plan_medicine_num]['time'] >= 18 && $plan_treatments[$next_plan_medicine_num]['time'] < 24) { ?>
											<option value="morning">Утром</option>
											<option value="afternoon">Днем</option>
											<option value="evening" selected>Вечером</option>
										<?php } else { ?>
											<option value="morning">Утром</option>
											<option value="afternoon" selected>Днем</option>
											<option value="evening">Вечером</option>
										<?php } ?>
										</select>
									</div>
									<?php
									$next_plan_medicine_num++;
									}
									?>
								</div>
							</div>
							<?php
							$plan_medicine_num++;
							$plan_treatment_day++;
							}
							}
							?>
						</div>
					</div>
				</div>
			</div>
			<!--//doctor Content-->
		</div>
		<!-- //Page Wrapper -->
		<!-- JQuery -->
		<script type="text/javascript" src="/public/scripts/jquery.js"></script>
		<!-- Custom -->
		<script src="/public/scripts/scripts.js"></script>
		<!-- Loading Bar -->
		<script src="/public/scripts/loading-bar.js"></script>
		<script>
			var bar1 = new ldBar("#myBar", {
				"preset": "bubble",
				"fill-background": '#9df',
				"fill-background-extrude":2,
				"value": 0,
				"data-precision":0.1,
			});
			bar1.set(<?php echo (100 - $cond); ?>);
		</script>
	</body>
</html>