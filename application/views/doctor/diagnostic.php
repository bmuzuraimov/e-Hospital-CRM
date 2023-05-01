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
					<h2 class="page-title">Диагностика Пациента <?=$name;?></h2>
					<form action="/doctor/updated" method="post">
						<input type="hidden" name="patientid" value="<?=$patientid;?>" />
						<div>
							<h3>Хронические Заболевания</h3>
							<input type="radio" class="diagnostic-checkbox" id="disease_1" value="100" name="illness"/>
							<label class="text-input" for="disease_1">&nbsp;Сердечно-сосудистые заболевания</label>
							<input type="radio" class="diagnostic-checkbox" id="disease_2" value="69.3" name="illness" />
							<label class="text-input" for="disease_2">&nbsp;Диабет</label>
							<input type="radio" class="diagnostic-checkbox" id="disease_3" value="63.7" name="illness" />
							<label class="text-input" for="disease_3">&nbsp;Гипертония</label>
							<input type="radio" class="diagnostic-checkbox" id="disease_4" value="60.2" name="illness" />
							<label class="text-input" for="disease_4">&nbsp;Хронические заболевания дыхательных путей</label>
							<input type="radio" class="diagnostic-checkbox" id="disease_5" value="57" name="illness" />
							<label class="text-input" for="disease_5">&nbsp;Рак</label>
							<input type="radio" class="diagnostic-checkbox" id="disease_6" value="0" name="illness" />
							<label class="text-input" for="disease_6">&nbsp;Другое</label>
						</div>
						<div>
							<label>Лабораторные данные</label>
							<select name="lab_data" class="text-input">
								<option disabled selected></option>
								<option value="I">I-(0)</option>
								<option value="II">II-(А)</option>
								<option value="III">III-(В)</option>
								<option value="IV">IV-(АВ)</option>
							</select>
						</div>
						<div>
							<h3>Возраст</h3>
							<?=$age;?>
						</div>
						<div>
							<h3>Пол</h3>
							<?=$sex;?>
						</div>
						<div>
							<h4>Поражение легких (трудность дыхания)</h4>
							<input type="radio" value="20" name="breath" class="diagnostic-checkbox" id="breath_1" required/>
							<label class="text-input" for="breath_1">&nbsp;0-10%</label>
							<input type="radio" value="40" id="breath_2" name="breath" class="diagnostic-checkbox"/>
							<label class="text-input" for="breath_2">&nbsp;10-20%</label>
							<input type="radio" value="60" id="breath_3" name="breath" class="diagnostic-checkbox"/>
							<label class="text-input" for="breath_3">&nbsp;20-40%</label>
							<input type="radio" value="80" id="breath_4" name="breath" class="diagnostic-checkbox"/>
							<label class="text-input" for="breath_4">&nbsp;40-60%</label>
							<input type="radio" value="100" id="breath_5" name="breath" class="diagnostic-checkbox"/>
							<label class="text-input" for="breath_5">&nbsp;>60%</label>
						</div>
						<div>
							<h3>Температура Тела</h3>
							<p id="temp">36</p>
							<input type="range" id="bar" name="temp" min="36" max="40" low="37" high="38.5" optimum="40" value="36" step="0.5" onchange="updateTextInput(this.value);">
						</div>
						<div>
							<h3>Слабость</h3>
							<input type="radio" id="weakness_1" class="diagnostic-checkbox" value="20" name="weakness" required/>
							<label for="weakness_1" class="text-input">&nbsp;1</label>
							<input type="radio" id="weakness_2" class="diagnostic-checkbox" value="40" name="weakness" />
							<label for="weakness_2" class="text-input">&nbsp;2</label>
							<input type="radio" id="weakness_3" class="diagnostic-checkbox" value="60" name="weakness" />
							<label for="weakness_3" class="text-input">&nbsp;3</label>
							<input type="radio" id="weakness_4" class="diagnostic-checkbox" value="80" name="weakness" />
							<label for="weakness_4" class="text-input">&nbsp;4</label>
							<input type="radio" id="weakness_5" class="diagnostic-checkbox" value="100" name="weakness" />
							<label for="weakness_5" class="text-input">&nbsp;5</label>
						</div>
						<div class="terms">
							<hr>
							<input type="checkbox" name="agreement" value="check" id="agree" class="agree-checkbox" required />
							<label for="agree" class="text-input agree">&nbsp;Я подтверждаю, что все указанные данные пациента являются достоверными.</label>
							<hr>
						</div>
						<div class="submit">
							<button type="submit" name="submit" class="btn btn-big">Сохранить</button>
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