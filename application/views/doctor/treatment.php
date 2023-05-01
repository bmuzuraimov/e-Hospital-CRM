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
		<link rel="stylesheet" type="text/css" href="/public/styles/treatment.css">
		<link rel="icon" href="/public/images/icons/icon_doctor.ico" type="image/icon">
		<title>Назначение лекарства</title>
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
					<h2 class="page-title">Лечение пациента <?=$patient['lname']." ".$patient['fname'];?></h2>
					<form action="/doctor/updatet" method="post" class="form">
						<?php
						$time = date('H:i', time()); ?>
						<table>
							<ul class="progressbar">
								<li <?php if ($time > '05:00' && $time < '12:00') echo "class=\"active\"" ;?>>Утренний (05:00-12:00)</li>
								<li <?php if ($time >= '12:00' && $time < '18:00') echo "class=\"active\"" ;?>>Дневной (12:00-18:00)</li>
								<li <?php if ($time > '18:00' && $time < '23:59') echo "class=\"active\"" ;?>>Вечерний (18:00-00:00)</li>
							</ul>
						</table>
						<?php 
						$medicine_num = 0;
						foreach ($plan_treatment as $treatment) { ?>
							<div class="medicine-block">
								<input type="text" class="text-input medicine" list="list_<?=$medicine_num;?>" name="medicine_<?=$medicine_num;?>" id="medicine_<?=$medicine_num;?>" placeholder="Найти лекарство" value="<?=$treatment['name'];?>" autocomplete="off" />
								<input class="text-input dose" id="dose_<?=$medicine_num;?>" type="text" name="ndose" value="<?=$treatment['dose']. ' мг';?>" placeholder="мг" onchange ="appendText(this.id, this.value);" autocomplete="off"/>
								<datalist id="list_<?=$medicine_num;?>"></datalist>
							</div>
						<?php
						$medicine_num++; 
						}
						 ?>
						<button type="button" class="edit" onclick="add_medicine();"><i class="fas fa-plus-circle"></i>&nbsp;Добавить</button>
						<?php
						if($isChecked==true){
						?>
						<div>
							<h4>Состояние пациента</h4><br>
							<p id="temp">0</p><br>
							<input type="range" class="text-input range" id="bar" name="health" min="0" max="100" low="10" high="50" optimum="80" value="0" step="2" onchange="updateTextInput(this.value);">
						</div>
						<div class="terms">
							<hr>
							<input type="checkbox" name="agreement" value="check" id="agree" class="agree-checkbox" required />
							<label for="agree" class="text-input agree">&nbsp;Я подтверждаю, что все указанные данные пациента являются достоверными.</label>
							<hr>
						</div>
						<?php
						}
						?>
						<input type="hidden" name="patientid" value="<?=$patientid;?>"/>
						<div class="animated-submit">
							<button type="submit" name="submit" class="btn btn-big">Подтвердить</button>
						</div>
					</form>
				</div>
			</div>
			<!--//doctor Content-->
		</div>
		<!-- //Page Wrapper -->
		<!-- JQuery -->
		<script type="text/javascript" src="/public/scripts/jquery.js"></script>
		<!-- Custom -->
		<script src="/public/scripts/scripts.js"></script>
		<!-- Suggestion -->
		<script type="text/javascript">
			$(document).ready(function(){
				$(".medicine").keyup(function(){
					var medicine_id = $(this).attr('id');
					var medicine_name = $(this).val();
					medicine_id = medicine_id.substring(9);
					$.get("/doctor/searchmedicine", {medicine: medicine_name}, function(data){
						$("#list_"+medicine_id).empty();
						suggested_name = data.slice(15, -2);
						if (suggested_name != medicine_name) {
							$("#list_"+medicine_id).html(data);
						}
					});
				});
			});
			function add_medicine() {
				var max = 0;
				$('.medicine-block').each(function() {
					var id = this.id.substring(15);
			    	max = Math.max(id, max);
				});
				max++;
				if ($(".medicine-block")[0]){
					$($( ".medicine-block" ).last()).after("<div class=\"medicine-block\"><input type=\"text\" class=\"text-input medicine\" list=\"list_"+max+"\" name=\"medicine_"+max+"\" id=\"medicine_"+max+"\" placeholder=\"Найти лекарство\" autocomplete=\"off\"/><input class=\"text-input dose\" id=\"dose_"+max+"\" type=\"text\" name=\"dose_"+max+"\" placeholder=\"мг\" onchange = \"appendText(this.id, this.value);\" autocomplete=\"off\"/><datalist id=\"list_"+max+"\"></datalist></div>");
				} else {
					$("table").after("<div class=\"medicine-block\"><input type=\"text\" class=\"text-input medicine\" list=\"list_"+max+"\" name=\"medicine_"+max+"\" id=\"medicine_"+max+"\" placeholder=\"Найти лекарство\" autocomplete=\"off\"/><input class=\"text-input dose\" id=\"dose_"+max+"\" type=\"text\" name=\"dose_"+max+"\" placeholder=\"мг\" onchange = \"appendText(this.id, this.value);\" autocomplete=\"off\"/><datalist id=\"list_"+max+"\"></datalist></div>");					
				}
			}
		</script>
	</body>
</html>