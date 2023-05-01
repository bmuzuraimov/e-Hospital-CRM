<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" conternt="ie=edge">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link href="https://fonts.googleapis.com/css2?family=Lora:wght@500&family=Merriweather:wght@300&family=Sansita+Swashed:wght@300;400&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="/public/styles/style.css">
		<link rel="stylesheet" href="/public/styles/signin.css">
		<title>Baiel.ai - Регистрация</title>
		<link rel="icon" href="/public/images/icons/icon.ico" type="image/icon">
	</head>
	<body>
		<header>
			<div class="logo">
				<h1 class="logo-text"><a href="/"><span>B</span>aiel-<span>A</span>I</a></h1>
			</div>
			<i class="fa fa-bars menu-toggle"></i>
			<ul class="nav">
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
		<div class="auth-content sign-up">
			<form action="/signup" method="post" id="add-hospital-form">
				<h2 class="form-title">Регистрация</h2>
				<div class="msg">
					<li></li>
				</div>
				<div>
					<label>Название Больницы<span>*</span></label>
					<input type="text" name="name" class="text-input" required/>
				</div>
				<div>
					<label>Адрес<span>*</span></label>
					<input type="text" name="address" class="text-input" required/>
				</div>
				<div>
					<label>Город<span>*</span></label>
					<input id="city_input" type="text" name="city" class="text-input" required/>
				</div>
				<div>
					<label>Область</label><br>
					<select name="oblast" class="text-input">
						<option class="oblast" selected disabled selected></option>
						<option class="oblast" value="Баткенская область" >Баткенская область</option>
						<option class="oblast" value="Джалал-Абадская область">Джалал-Абадская область</option>
						<option class="oblast" value="Иссык-Кульская область">Иссык-Кульская область</option>
						<option class="oblast" value="Нарынская область">Нарынская область</option>
						<option class="oblast" value="Ошская область">Ошская область</option>
						<option class="oblast" value="Таласская область">Таласская область</option>
						<option class="oblast" value="Чуйская область">Чуйская область</option>
					</select>
				</div>
				<div>
					<label>Должность<span>*</span></label>
					<input type="text" name="position" class="text-input" id="position" disabled value="Главный врач"/>
				</div>
				<div>
					<label>Всего Коек<span>*</span></label>
					<input type="number" name="tunits" class="text-input" required/>
				</div>
				<div>
					<label>ИНН<span>*</span></label>
					<input type="number" name="passport" placeholder="20101199100001" class="text-input" autocomplete="off" required/>
				</div>
				<div>
					<label>Номер телефона<span>*</span></label>
					<input type="text" name="phone" placeholder="077XXXXXXX" class="text-input" autocomplete="off" required/>
				</div>
				<div>
					<label>Пароль<span>*</span></label>
					<input type="password" name="passwd" required class="text-input" autocomplete="off"/>
				</div>
				<div class="animated-submit">
					<button type="submit" name="submit" class="btn btn-big" id="add-hospital-btn">Регистрация</button>
				</div>
				<p>Или <a href="/sign_in">Войдите</a></p>
			</form>
		</div>
		<!-- JQuery -->
		<script type="text/javascript" src="/public/scripts/jquery.js"></script>
		<!-- Custom -->
		<script src="/public/scripts/autocomplete.js"></script>
		<script type="text/javascript">
		$(document).ready(function() {
			var msg_type = {created: "success", exists: "error", empty: "error"};
			var messages = {created: "Ваш аккаунт успешно создан! Ждите подтверждения от модераторов", exists: "Такой номер телефона или ИНН уже существует!", empty: "Заполните поля!"};
		$("#add-hospital-btn").click(function(){
		$.post($("#add-hospital-form").attr("action"), $("#add-hospital-form :input").serializeArray(), function(info){
			$(".text-input:not(#position)").val("");
						$(".msg").addClass(msg_type[info]);
						$(".msg li").html(messages[info]);
						$('html, body').animate({scrollTop: 0}, 'slow');
		});
		});
		$("#add-hospital-form").submit(function(){
		return false;
		});
		});
		</script>
	</body>
</html>