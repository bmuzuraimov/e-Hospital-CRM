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
		<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="/public/styles/style.css">
		<link rel="stylesheet" href="/public/styles/signin.css">
		<title>Отправить запрос</title>
		<link rel="icon" href="/public/images/icons/icon.ico" type="image/icon">
	</head>
	<body>
		<header>
			<div class="logo">
				<h1 class="logo-text"><a href="/"><span>B</span>aiel-<span>A</span>I</a></h1>
			</div>
			<i class="fa fa-bars menu-toggle"></i>
			<ul class="nav">
				<?php if (isset($_SESSION['service']) && $_SESSION['service']!=""): ?>
				<li>
					<a href="#">Мой профиль<i class="fa fa-chevron-down"></i></a>
					<ul>
						<?php if ($_SESSION['service']==1){ ?>
						<li><a href="/admin">Панель управления</a></li>
						<li><a href="/admin/logout" class="logout">Выйти</a></li>
						<?php }elseif ($_SESSION['service']==103) { ?>
						<li><a href="/hospital">Панель управления</a></li>
						<li><a href="/hospital/logout" class="logout">Выйти</a></li>
						<?php }else{ ?>
						<li><a href="/doctor">Панель управления</a></li>
						<li><a href="/doctor/logout" class="logout">Выйти</a></li>
						<?php } ?>
					</ul>
				</li>
				<?php else: ?>
				<li><a href="/sign_in">Войти</a></li>
				<li><a href="/sign_up">Регистрация</a></li>
				<?php endif ?>
				<li><a href="/treatment_plan">План Лечения</a></li>
				<li><a href="/closest_hospital">Ближайшая Больница</a></li>
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
			</ul>
		</div>
		<!-- Page Wrapper -->
		<div class="page-wrapper">
			<!-- Content -->
			<div class="content clearfix">
				<!-- Main Content -->
				<div class="auth-content sign-up">
					<h2 class="form-title">Получить Рекомендацию</h2>
					<form action="/doctor/addp" method="post">
						<div>
							<label>Имя</label>
							<input type="text" name="fname" class="text-input" required/>
						</div>
						<div>
							<label>Фамилия</label>
							<input type="text" name="lame" class="text-input" required/>
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
							<label>Лабораторные данные</label>
							<select name="blood_type" class="text-input">
								<option disabled selected></option>
								<option value="I" >I-(0)</option>
								<option value="II" >II-(А)</option>
								<option value="III" >III-(В)</option>
								<option value="IV" >IV-(АВ)</option>
							</select>
						</div>
						<div>
							<label>Номер телефона для ЧС</label>
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
						<div class="submit">
							<button type="submit" name="submit" class="btn btn-big">Отправить</button>
						</div>
					</form>
				</div>
				<!-- //Main Content -->
			</div>
			<!-- //Content -->
		</div>
		<!-- //Page Wrapper -->
		<!-- Footer -->
		<?php include('application/include/main_footer.html'); ?>
		<!-- //Footer -->
		<!-- JQuery -->
		<script type="text/javascript" src="/public/scripts/jquery.js"></script>
		<!-- Slick -->
		<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
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
			$( ".message" ).html(info);
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