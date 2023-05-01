<?php
function getAge($dateofbirth){
	$today = date("Y-m-d");
	$diff = date_diff(date_create($dateofbirth), date_create($today));
	$diff = $diff->format('%y');
	return $diff;
}
function remaining_days($toDate)
{
$today = date("Y-m-d");
if ($toDate != false) {
$diff = date_diff(date_create($toDate), date_create($today));
$diff = $diff->format('%d');
return $diff;
} else {
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
function profile_img($sex, $age)
{
if ($sex == 'male') {
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
} else {
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
$illness  = $risk['illness'];
$age      = $risk['age'];
$sex      = $risk['sex'];
$breath   = $risk['breath'];
$temp     = $risk['temperature'];
$weakness = $risk['weakness'];
$illness *= $illnessrisk;
$age *= $agerisk;
$temp = ((($temp - 36) * (100)) / (40 - 36));
$temp *= $temprisk;
$sex *= $genderrisk;
$breath *= $breathrisk;
$weakness *= $weaknessrisk;
$cond = $illness + $age + $temp + $sex + $breath + $weakness;
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
		<link rel="stylesheet" href="/public/styles/hospital.css">
		<link rel="stylesheet" type="text/css" href="/public/styles/profile.css">
		<link rel="stylesheet" type="text/css" href="/public/styles/loading-bar.css"/>
		<link rel="stylesheet" href="/public/styles/morris.css">
		<link rel="icon" href="/public/images/icons/icon_hospital.ico" type="image/icon">
		<title><?=$patient['fname'] . " " . $patient['lname'];?></title>
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
					<h3 class="page-title">Статус: <?=$status;?></h3>
					<div class="wrapper">
						<div class="hospital-patient-left">
							<img src="<?=profile_img($patient['sex'], getAge($patient['age']));?>" alt="profile picture">
							<h4 class="page-title"><?=$patient['fname'] . " " . $patient['lname'];?></h4>
							<a href="#">ИНН: <?=$patient['passport'];?></a>
							<h5 class="publish"><a href="tel:<?=$patient['emcontact']?>"><?=$patient['emcontact']?></a></h5>
						</div>
						<div class="right">
							<div class="info">
								<form id="edit-hospital" method="get" action="/hospital/editp">
									<input type="hidden" name="patientid" value=<?=$patient["id"];?>>
									<button type="submit">Редактировать</button>
								</form><br><br>
								<h3>Инфо</h3>
								<div class="info-data">
									<div class="data">
										<h4>Дата рождения</h4>
										<p><?=$patient['age'];?></p>
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
					<?php
					if (!empty($patient_healths)) { ?>
						<h2 class="page-title">Состояние пациента</h2>
						<div class="graph" id="health" style=""></div>
					<?php } ?>
					<div class="wrapper">
						<div class="hospital-patient-left">
							<img src="<?=profile_img($matching_patient['sex'], getAge($matching_patient['age']));?>" alt="profile picture">
							<h4><?=$matching_patient['fname'] . " " . $matching_patient['lname'];?></h4>
							<a href="#">ИНН: <?=$patient['passport'];?></a>
							<h5 class="publish"><a href="tel:<?=$matching_patient['emcontact'];?>"><?=$matching_patient['emcontact'];?></a></h5>
						</div>
						<div class="right">
							<div class="info">
								<h3>Схожий пациент</h3>
								<div class="info-data">
									<div class="data">
										<h4>Дата рождения</h4>
										<p><?=$matching_patient['age'];?></p>
									</div>
									<div class="data">
										<h4>Поражение легких</h4>
										<p><?=assess_breath($matching_patient_risk['breath'])?></p>
									</div>
									<div class="data">
										<h4></h4>
									</div>
								</div>
							</div>
							<div class="extra-info">
								<h3>Здоровье</h3>
								<div class="extra-info-data">
									<div class="data">
										<h4>Хронические заболевания</h4>
										<p><?=illness_name($matching_patient_risk['illness'])?></p>
									</div>
									<div class="data" style="width: 100%;">
										<h4>Симптомы</h4>
										<p><?=$matching_patient['symptoms']?></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php 
					if (!empty($matching_patient_healths)) { ?>
						<h2 class="page-title">Состояние пациента</h2>
					<?php } ?>
					<div class="graph" id="matching_health" style=""></div>
					<div class="tab" id="tab" style="grid-template-columns: auto auto;">
						<a href="/hospital/viewp?patientid=<?=$patient['id']?>&treatmentplan=history#tab">История лечений</a>
						<a href="/hospital/viewp?patientid=<?=$patient['id']?>&treatmentplan=plan#tab">План Лечения</a>
						<a href="/hospital/viewp?patientid=<?=$patient['id']?>&treatmentplan=ai#tab">Рекоментация</a>
						<a href="/hospital/viewp?patientid=<?=$patient['id']?>&treatmentplan=custom#tab">Создать план</a>
					</div>
					<div class="treatmentplan">
						<?php if (empty($_GET['treatmentplan']) || $_GET['treatmentplan']=="history") { ?>
						<div class="timeline-area">
						<h1 class="page-title">История Лечения</h1>
							<?php
							$treatment_history_day = 1;
							if ($my_treatment_days != false) {
							for ($i = 0; $i < $my_treatment_days[0]; $i++) {
							$next_date = $i + 1;?>
							<div class="treatment history">
								<h3 class="page-title"><?="День $treatment_history_day"?></h3>
								<div class="day-treatment">
									<div class="medicine">
										<input type="text" class="text-input" name="medicine" value="<?=$my_treatments[$i]['name']?>" disabled>
										<input type="text" class="text-input" name="dose" value="<?=$my_treatments[$i]['dose'] . " мг"?>" disabled>
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
										<input type="text" class="text-input" name="dose" value="<?=$my_treatments[$next_date]['dose'] . " мг"?>" disabled>
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
							</div>
						<?php } elseif ($_GET['treatmentplan']=='ai') { ?>
						<h2 class="page-title">Рекомендация</h2>
						<form action="/hospital/savetreatment" method="post" class="timeline-area" id="save-treatment-form">
							<input type="hidden" name="patientid" value="<?=$patient['id'];?>">
							<?php
							if ($ai_treatment_days != false) {
							$ai_treatment_day = 1;
							$ai_medicine_num = 0;
							for ($i = 0; $i < $ai_treatment_days[0]; $i++) {
							$next_ai_medicine_num = $ai_medicine_num + 1;
							?>
							<div class="treatment ai">
								<h3 class="page-title"><?="День $ai_treatment_day"?></h3>
								<div class="day-treatment" id='<?="day-".$ai_treatment_day?>'>
									<div class="medicine-block" id='<?="medicine-block-".$ai_medicine_num?>'>
										<div class="btn-delete">
											<button type="button" id='<?="remove_".$ai_medicine_num?>' onclick="remove_medicine(this.id, 'ai');">X</button>
										</div>
										<input type="text" class="text-input medicine-input" id="medicine-input-id-<?=$ai_medicine_num;?>" list="list_<?=$ai_medicine_num;?>" name="medicine_<?=$ai_medicine_num;?>" value="<?=$ai_treatments[$ai_medicine_num]['name']?>">
										<datalist id="list_<?=$ai_medicine_num;?>"></datalist>
										<input type="text" class="text-input" name="dose_<?=$ai_medicine_num;?>" value="<?=$ai_treatments[$ai_medicine_num]['dose'] . " мг"?>">
										<select name="time_<?=$ai_medicine_num;?>" class="text-input">
										<?php
										if ($ai_treatments[$ai_medicine_num]['time'] > '05:00' && $ai_treatments[$ai_medicine_num]['time'] < '12:00') {
										?>
											<option value="morning" selected>Утром</option>
											<option value="afternoon">Днем</option>
											<option value="evening">Вечером</option>
										<?php } else if ($ai_treatments[$ai_medicine_num]['time'] >= '12:00' && $ai_treatments[$ai_medicine_num]['time'] < '18:00') {?>
											<option value="morning">Утром</option>
											<option value="afternoon" selected>Днем</option>
											<option value="evening">Вечером</option>
										<?php } else {?>
											<option value="morning">Утром</option>
											<option value="afternoon">Днем</option>
											<option value="evening" selected>Вечером</option>
										<?php }?>
										</select>
										<input type="hidden" name="day_<?=$ai_medicine_num;?>" value="<?=$ai_treatment_day?>">
									</div>
									<?php
									while ($next_ai_medicine_num<count($ai_treatments) && $ai_treatments[$ai_medicine_num]['date'] == $ai_treatments[$next_ai_medicine_num]['date']) {
									$ai_medicine_num++;
									?>
									<div class="medicine-block" id='<?="medicine-block-".$ai_medicine_num?>'>
										<div class="btn-delete">
											<button type="button" id='<?="remove_".$ai_medicine_num?>' onclick="remove_medicine(this.id, 'ai');">X</button>
										</div>
										<input type="text" class="text-input medicine-input" id="medicine-input-id-<?=$ai_medicine_num;?>" list="list_<?=$ai_medicine_num;?>" name="medicine_<?=$ai_medicine_num;?>" value="<?=$ai_treatments[$next_ai_medicine_num]['name']?>">
										<datalist id="list_<?=$ai_medicine_num;?>"></datalist>
										<input type="text" class="text-input" name="dose_<?=$ai_medicine_num;?>" value="<?=$ai_treatments[$next_ai_medicine_num]['dose'] . " мг"?>">
										<select name="time_<?=$ai_medicine_num;?>" class="text-input">
										<?php
										if ($ai_treatments[$next_ai_medicine_num]['time'] > '05:00' && $ai_treatments[$next_ai_medicine_num]['time'] < '12:00') {
										?>
											<option value="morning" selected>Утром</option>
											<option value="afternoon">Днем</option>
											<option value="evening">Вечером</option>
										<?php } else if ($ai_treatments[$next_ai_medicine_num]['time'] >= '12:00' && $ai_treatments[$next_ai_medicine_num]['time'] < '18:00') {?>
											<option value="morning">Утром</option>
											<option value="afternoon" selected>Днем</option>
											<option value="evening">Вечером</option>
										<?php } else {?>
											<option value="morning">Утром</option>
											<option value="afternoon">Днем</option>
											<option value="evening" selected>Вечером</option>
										<?php }?>
										</select>
										<input type="hidden" name="day_<?=$ai_medicine_num;?>" value="<?=$ai_treatment_day;?>">
									</div>
									<?php
									$next_ai_medicine_num++;
									}
									?>
								</div>
								<div class="btn-add">
									<button type="button" id='<?="add_".$ai_treatment_day?>' onclick="add_medicine(this.id);">+</button>
								</div>
							</div>
							<?php
							$ai_medicine_num++;
							$ai_treatment_day++;
							}
							}
							if ($ai_treatment_days != false) {
							?>
						<div class="animated-submit">
							<button type="submit" name="submit" class="btn btn-big" id="save-treatment-btn">Сохранить</button>
						</div>
						<?php } ?>
						</form>
						<?php } elseif ($_GET['treatmentplan']=='custom') { ?>
						<div>
							<h3 class="page-title">Дней лечения</h3>
							<input type="number" class="text-input" id="manual-days-input" style="text-align:center;">
						</div>
						<form action="/hospital/savetreatment" method="post" class="timeline-area" id="save-treatment-form">

						</form>						
						<?php } elseif ($_GET['treatmentplan']=='plan') { ?>
						<h1 class="page-title">План Лечения</h1>
						<form action="/hospital/savetreatment" method="post" class="timeline-area" id="save-treatment-form">
						<input type="hidden" name="patientid" value="<?=$patient['id'];?>">
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
										<div class="btn-delete">
											<button type="button" id='<?="remove_".$plan_medicine_num?>' onclick="remove_medicine(this.id, 'ai');">X</button>
										</div>
										<input type="text" class="text-input medicine-input" id="medicine-input-id-<?=$plan_medicine_num;?>" list="list_<?=$plan_medicine_num;?>" name="medicine_<?=$plan_medicine_num;?>" value="<?=$plan_treatments[$plan_medicine_num]['name']?>">
										<datalist id="list_<?=$plan_medicine_num;?>"></datalist>
										<input type="text" class="text-input" name='<?="dose_<?=$plan_medicine_num;?>".$plan_medicine_num?>' value="<?=$plan_treatments[$plan_medicine_num]['dose'] . " мг"?>">
										<select name="time_<?=$plan_medicine_num;?>" class="text-input">
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
										<div class="btn-delete">
											<button type="button" id='<?="remove_".$plan_medicine_num?>' onclick="remove_medicine(this.id, 'ai');">X</button>
										</div>
										<input type="text" class="text-input medicine-input" id="medicine-input-id-<?=$plan_medicine_num;?>" list="list_<?=$plan_medicine_num;?>" name="medicine_<?=$plan_medicine_num;?>" value="<?=$plan_treatments[$next_plan_medicine_num]['name']?>">
										<datalist id="list_<?=$plan_medicine_num;?>"></datalist>
										<input type="text" class="text-input" name="dose_<?=$plan_medicine_num;?>" value="<?=$plan_treatments[$next_plan_medicine_num]['dose'] . " мг"?>">
										<select name="time_<?=$plan_medicine_num;?>" class="text-input">
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
										<input type="hidden" name="day_<?=$plan_medicine_num;?>" value="<?=$plan_treatment_day?>">
									</div>
									<?php
									$next_plan_medicine_num++;
									}
									?>
								</div>
								<div class="btn-add">
									<button type="button" id='<?="add_".$plan_treatment_day?>' onclick="add_medicine(this.id);">+</button>
								</div>
							</div>
							<?php
							$plan_medicine_num++;
							$plan_treatment_day++;
							}
							}
							if ($plan_treatment_days != false) { ?>
							<div class="animated-submit">
								<button type="submit" name="submit" class="btn btn-big" id="save-treatment-btn">Сохранить</button>
							</div>
							<?php } ?>
						</form>
						<?php } else { ?>
						<h1 class="page-title error">Возникла Ошибка</h1>
						<?php }
						?>
					</div>
				</div>
			</div>
			<!--//Hospital Content-->
		</div>
		<!-- //Page Wrapper -->
		<!-- JQuery -->
		<script type="text/javascript" src="/public/scripts/jquery.js"></script>
	    <!-- Custom -->
	    <script src="/public/scripts/scripts.js"></script>
		<!-- Loading Bar -->
		<script src="/public/scripts/loading-bar.js"></script>
		<!-- Morris -->
		<script src="/public/scripts/morris.js"></script>
		<!-- Raphael-min -->
		<script src="/public/scripts/raphael-min.js"></script>
		<script type="text/javascript">
		$(document).ready(function() {
		    $("#save-treatment-btn").click(function(){
		        $.post($("#save-treatment-form").attr("action"), $("#save-treatment-form :input").serializeArray(), function(info){
		        	$( "#save-treatment-btn" ).html("Сохранено");
		        });
		    });
		    $("#save-treatment-form").submit(function(){
		        return false;
		    });
		    $(".text-input").change(function(){
    			$( "#save-treatment-btn" ).html("Сохранить");
    		});
			$('#manual-days-input').keyup(function () {
				var days = $(this).val();
				var med_counter_1 = 1;
				var med_counter_2 = 2;
				var med_counter_3 = 3;
				if(days!='' && days!=0 && days<15){
				$( "#save-treatment-form" ).empty();
				$("#save-treatment-form").append("<input type=\"hidden\" name=\"patientid\" value=\"<?=$patient['id'];?>\">");
				for (var i = 1; i <= days; i++) {
				$("#save-treatment-form").append(
				"<div class=\"treatment custom\"><h3 class=\"page-title\">День "+i+"</h3><div class=\"day-treatment\" id='day-"+i+"'><div class=\"medicine-block\" id='medicine-block-"+med_counter_1+"'><div class=\"btn-delete\"><button type=\"button\" id=\"delete_"+med_counter_1+"\" onclick=\"remove_medicine(this.id, 'manual');\">X</button></div><input type=\"text\" class=\"text-input\" name=\"medicine_"+i+med_counter_1+"\" placeholder='Название лекарства'><input type=\"text\" class=\"text-input\" name=\"dose_"+i+med_counter_1+"\" placeholder='Дозировка'><select name=\"time_"+i+med_counter_1+"\" class=\"text-input\"><option value=\"morning\" selected>Утром</option><option value=\"afternoon\">Днем</option><option value=\"evening\">Вечером</option></select><input type=\"hidden\" name=\"day_"+med_counter_1+"\" value=\""+i+"\"></div><div class=\"medicine-block\" id='medicine-block-"+med_counter_2+"'><div class=\"btn-delete\"><button type=\"button\" id=\"delete_"+med_counter_2+"\" onclick=\"remove_medicine(this.id, 'manual');\">X</button></div><input type=\"text\" class=\"text-input\" name=\"medicine_"+i+med_counter_2+"\" placeholder='Название лекарства'><input type=\"text\" class=\"text-input\" name=\"dose_"+i+med_counter_2+"\" placeholder='Дозировка'><select name=\"time_"+i+med_counter_2+"\" class=\"text-input\"><option value=\"morning\">Утром</option><option value=\"afternoon\" selected>Днем</option><option value=\"evening\">Вечером</option></select><input type=\"hidden\" name=\"day_"+med_counter_2+"\" value=\""+i+"\"></div><div class=\"medicine-block\" id='medicine-block-"+med_counter_3+"'><div class=\"btn-delete\"><button type=\"button\" id=\"delete_"+med_counter_3+"\" onclick=\"remove_medicine(this.id, 'manual');\">X</button></div><input type=\"text\" class=\"text-input\" name=\"medicine_"+i+med_counter_3+"\" placeholder='Название лекарства'><input type=\"text\" class=\"text-input\" name=\"dose_"+i+med_counter_3+"\" placeholder='Дозировка'><select name=\"time_"+i+med_counter_3+"\" class=\"text-input\"><option value=\"morning\">Утром</option><option value=\"afternoon\">Днем</option><option value=\"evening\" selected>Вечером</option></select><input type=\"hidden\" name=\"day_"+med_counter_3+"\" value=\""+i+"\"></div></div><div class=\"btn-add\"><button type=\"button\" id='add_"+i+"' onclick=\"add_medicine(this.id);\">+</button></div></div>"
				);
				med_counter_1+=3;
				med_counter_2+=3;
				med_counter_3+=3;
				}
				$("#save-treatment-form").append("<div class=\"submit\" style=\"text-align: center;\"><button type=\"submit\" name=\"submit\" class=\"btn btn-big\" id=\"custom-treatment-btn\">Сохранить</button></div>");
				}else{
					$( "#save-treatment-form" ).empty();
				}
			});
			$(document).ready(function(){
				$(".medicine-input").keyup(function(){
					var medicine_id = $(this).attr('id');
					var medicine_name = $(this).val();
					medicine_id = medicine_id.substring(18);
					$.get("/hospital/searchmedicine", {medicine: medicine_name}, function(data){
						$("#list_"+medicine_id).empty();
						suggested_name = data.slice(15, -2);
						if (suggested_name != medicine_name) {
							$("#list_"+medicine_id).html(data);
						}
					});
				});
			});
		});
		var bar1 = new ldBar("#myBar", {
			"preset": "bubble",
			"fill-background": '#9df',
			"fill-background-extrude":2,
			"value": 0,
			"data-precision":0.1,
		});
		bar1.set(<?php echo (100 - $cond); ?>);
		<?php if (!empty($patient_healths)) { ?>
		Morris.Area({
			element: 'health',
			redraw: true,
			behaveLikeLine: true,
			data: [
			<?php foreach ($patient_healths as $health):
			echo "{date: '" . $health['data'] . "', y: '" . $health['improvement'] . "'},";
			endforeach; ?>
			],
			parseTime: false,
			xkey: 'date',
			ykeys: 'y',
			labels: ['Состояние', 'y']
		});
		<?php }
		if (!empty($matching_patient_healths)) { ?>
		Morris.Area({
			element: 'matching_health',
			redraw: true,
			behaveLikeLine: true,
			data: [
			<?php foreach ($matching_patient_healths as $health):
			echo "{date: '" . $health['data'] . "', y: '" . $health['improvement'] . "'},";
			endforeach; ?>
			],
			parseTime: false,
			xkey: 'date',
			ykeys: 'y',
			labels: ['Состояние', 'y']
		});
		<?php } ?>
		function remove_medicine(id, type) {
			if(type=="ai"){
				id = id.substring(7);
				item = "#medicine-block-"+id;
				$(item).remove();
			}else{
				id = id.substring(7);
				item = "#medicine-block-"+id;
				$(item).remove();
			}
		}
		function add_medicine(day_id) {
			var max = 0;
			$('.medicine-block').each(function() {
				var id = this.id.substring(15);
			    max = Math.max(id, max);
			});
			max++;
			day_id = day_id.substring(4);
			item = "#day-"+day_id;
			$(item).append("<div class=\"medicine-block\" id='medicine-block-"+max+"'><div class=\"btn-delete\"><button type=\"button\" id=\"remove_"+max+"\" onclick=\"remove_medicine(this.id, 'ai');\">X</button></div><input type=\"text\" class=\"text-input medicine-input\" id=\"medicine-input-id-"+max+"\" list=\"list_"+max+"\" name=\"medicine_"+max+"\" placeholder='Название лекарства'><datalist id=\"list_"+max+"\"></datalist><input type=\"text\" class=\"text-input\" name=\"dose_"+max+"\" placeholder='Дозировка'><select name=\"time_"+max+"\" class=\"text-input\"><option value=\"morning\" selected>Утром</option><option value=\"afternoon\">Днем</option><option value=\"evening\">Вечером</option></select><input type=\"hidden\" name=\"day_"+max+"\" value=\""+day_id+"\"></div>");
		}
		</script>
	</body>
</html>