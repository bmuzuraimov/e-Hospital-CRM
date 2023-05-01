<?php
$symptoms_num = count($symptoms);
$windows_num = floor($symptoms_num/8);
$remainder = $symptoms_num%8;
$windows_num = ($remainder>0)?$windows_num+1:$windows_num;
$grid_percentage = floor(100/$windows_num);
$windows = '';
$window_steps = array();
for ($i=0; $i < $windows_num; $i++) {
	$windows = $windows." ".strval($grid_percentage)."%";
	if (($windows_num-1)==$i) {
		$window_steps[] = $remainder;
	} else {
		$window_steps[] = 8;
	}
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
		<title>Добавить пациента</title>
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
					<h2 class="page-title">Добавить нового пациента</h2>
					<div class="message"></div>
					<form action="/doctor/addpatient" method="post" id="add-patient-form">
						<input type="hidden" name="hospitalid" value="<?=$hospitalid;?>">
						<div>
							<label>Имя</label>
							<input type="text" name="fname" class="text-input" required/>
						</div>
						<div>
							<label>Фамилия</label>
							<input type="text" name="lname" class="text-input" required/>
						</div>
						<div>
							<label>Дата Рождения</label>
							<input type="date" name="age" class="text-input" required/>
						</div>
						<div>
							<label>Пол</label>
							<select name="sex" class="text-input">
								<option disabled selected></option>
								<option value="male" >Мужской</option>
								<option value="female">Женский</option>
							</select>
						</div>
						<div>
							<label>ИНН</label>
							<input type="number" name="passport" placeholder="20101199100001" class="text-input"/>
						</div>
						<div>
							<label>Номер телефона:</label>
							<input type="tel" name="phone" placeholder="077XXXXXXX" class="text-input"/>
						</div>
						<h3 class="page-title" style="margin-top: 40px;">Что беспокоит</h3>
						<div>
						<textarea name="symptoms" class="text-input" id="textarea_symp"></textarea>
						</div>
						<div class="symptoms" style="display: grid;grid-template-columns: <?=$windows;?>;">
							<?php
							$start_counter = 0;
							$end_counter = $window_steps[0]; 
							for ($i=0; $i < $windows_num; $i++) { ?>
							<div>
								<?php 
								for ($k=$start_counter; $k < $end_counter; $k++) { ?>
									<label><?=$symptoms[$k]['name'];?><input type="checkbox" name="symptom" onClick="checkbox();" value="<?=$symptoms[$k]['name'];?>" class="check-box"/></label>
								<?php
								} 
								?>
							</div>
							<?php
								if ($i!=($windows_num-1)) {
									$start_counter = $end_counter;
									$end_counter = $start_counter+$window_steps[($i+1)];
								}
							} ?>
						</div>
						<div class="animated-submit">
							<button type="submit" name="submit" class="btn btn-big" id="add-patient-btn">Добавить</button>
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
		<script type="text/javascript">
		$(document).ready(function() {
		    $("#add-patient-btn").click(function(){
		        $.post($("#add-patient-form").attr("action"), $("#add-patient-form :input").serializeArray(), function(info){
		        	$( ".message" ).remove();
		            $(".text-input").val("");
		            $( ".check-box" ).prop( "checked", false );
		            $(".doctor-content").animate({ scrollTop: 0 }, "slow");
		        	$(".message").html(info);
		        	console.log(info);
		        });
		    });
		    $("#add-patient-form").submit(function(){
		        return false;
		    });
		});
		function checkbox(){
			var checkboxes = document.getElementsByName('symptom');
			var checkboxesChecked = [];
			for (var i=0; i<checkboxes.length; i++) {
				if (checkboxes[i].checked) {
					checkboxesChecked.push(checkboxes[i].value);
				}
			}
			document.getElementById("textarea_symp").value = checkboxesChecked;
		}
		</script>
	</body>
</html>