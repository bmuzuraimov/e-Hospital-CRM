<?php
if (isset($_SESSION['service'])){
	switch ($_SESSION['service']) {
		case '1':
			header('Location: /admin');
		exit;
		break;
		case '103':
			header('Location: /hospital');
		exit;
		break;
		case '100':
			header('Location: /doctor');
		exit;
		break;
		default:
			header('Location: /admin/logout');
		exit;
		break;
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
		<link rel="stylesheet" href="/public/styles/signin.css">
		<title>Baiel.ai - Вход</title>
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
		<div class="auth-content sign-in">
			<form action="/authenticate" method="post" autocomplete="off" id="sign-in-form">
				<h2 class="form-title">Логин</h2>
				<div class="msg">
					<li></li>
				</div>
				<div>
					<label>Номер телефона</label>
					<input type="text" name="username" class="text-input">
				</div>
				<div>
					<label>Пароль</label>
					<input type="password" name="password" class="text-input">
				</div>
				<div class="animated-submit">
					<button type="submit" name="submit" class="btn btn-big" id="sign-in-btn">Вход</button>
				</div>
				<p>Или <a href="/sign_up">Зарегистрируйтесь</a></p>
			</form>
		</div>
		<!-- JQuery -->
		<script type="text/javascript" src="/public/scripts/jquery.js"></script>
		<!-- Custom -->
		<script src="/public/scripts/scripts.js"></script>
		<script type="text/javascript">
		$(document).ready(function() {
			var messages = {empty: "Заполните все поля", valid: "Ваш аккаунт еще не подтвержден", password: "Неверный пароль", username: "Неверное имя пользователя"};
			var links = ["/admin", "/hospital", "/doctor"];
		$("#sign-in-btn").click(function(){
		$.post($("#sign-in-form").attr("action"), $("#sign-in-form :input").serializeArray(), function(info){
			if (links.includes(info)) {
				window.location.href = info;
			}else {
				$(".msg").addClass("error");
							$(".msg li").html(messages[info]);
			}
		});
		});
		$("#sign-in-form").submit(function(){
		return false;
		});
		});
		</script>
	</body>
</html>