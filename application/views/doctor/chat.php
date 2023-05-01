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
		<link rel="stylesheet" href="/public/styles/chath.css">
		<link rel="icon" href="/public/images/icons/icon_doctor.ico" type="image/icon">
		<title>Конференция</title>
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
					<div class="chatbox">
						<div class="chatlogs"></div>
						<div class="chat-form">
							<textarea id="textmessage" name="message" autocomplete="off" placeholder="Напишите что-то..." required></textarea>
							<a href="javascript:void(0)" class="submit"><i class="far fa-paper-plane"></i></a>
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
		<script type="text/javascript">
			function listMessages()
				{
					$.ajax({
						url:'/doctor/getm',
						success:function(res){
							$('.chatlogs').html(res);
						}
					})
				}
			$(function(){
				listMessages();
				setInterval(function(){
					listMessages();
				},10000);
				$('.submit').click(function(){
					var textmessage = $('#textmessage').val();
					$.ajax({
						url:'/doctor/chat',
						data:'message='+textmessage,
						type:'POST',
						success:function()
						{
							$('#textmessage').val('');
							listMessages();
							var last_message_pos = $('.chat-message :last-child').position();
							$('.chatlogs').animate({
        						scrollTop: last_message_pos.top
    						}, 'slow');
						}
					})
				})
			})
		</script>
	</body>
</html>