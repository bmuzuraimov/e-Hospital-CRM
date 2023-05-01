<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" conternt="ie=edge">
		<meta name="keywords" content="bai.el.kg, kyrgyzstan, artificial inteligence">
		<meta name="description" content="Baiel-AI Главная страница">
		<meta name="author" content="Baiel Muzuraimov">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link href="https://fonts.googleapis.com/css2?family=Lora:wght@500&family=Merriweather:wght@300&family=Sansita+Swashed:wght@300;400&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="/public/styles/style.css">
		<link rel="icon" href="/public/images/icons/icon.ico" type="image/icon">
		<title>Baiel-Ai - 1.3</title>
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
			
			<!-- Post Slider -->
			<div class="post-slider">
				<h1 class="slider-title">Популярные Посты</h1>
				<i class="fas fa-chevron-left prev"></i>
				<i class="fas fa-chevron-right next"></i>
				<div class="post-wrapper">
					<?php foreach ($popular_posts as $post): ?>
					<div class="post">
						<img src="<?=$post['profile_img']?>" class="slider-image">
						<div class="post-info">
							<h4><a href="/article?id=<?=$post['id']?>">
								<?php
								if (mb_strlen($post['title'])>28) {
									echo mb_substr($post['title'], 0, 28)."...";
								} else {
									echo $post['title'];
								}
								?>
							</a></h4>
							<p><i class="fas fa-circle-notch"></i>&nbsp;<?=mb_convert_case($post['category'], MB_CASE_TITLE, "UTF-8");?></p>
							<i class="far fa-calendar">&nbsp;<?=mb_substr($post['date'], 0, 10);?></i>&nbsp;
							<i class="far fa-user">&nbsp;<?=$post['author']?></i>
						</div>
					</div>
					<?php endforeach ?>
				</div>
			</div>
			<!-- //Post Slider -->
			<!-- Content -->
			<div class="content clearfix">
				<!-- Main Content -->
				<div class="main-content">
					<h1 class="recent-post-title">Недавние</h1>
					<?php
					foreach ($recent_posts as $post):
					$text = substr($post['text'], strpos($post['text'], "<p>") + 3);
							$text =  strip_tags($text);
							$text = mb_substr($text, 0, 340);
							$text .="...";
							$date = mb_substr($post['date'], 0, 10);
						?>
						<div class="post clearfix">
							<img src="<?=$post['profile_img']?>" class="post-image">
							<div class="post-preview">
								<h3><a href="/article?id=<?=$post['id']?>"><?=htmlspecialchars($post['title'])?></a></h3>
								<i class="far fa-user">&nbsp;<?=htmlspecialchars($post['author'])?></i>
								&nbsp;
								<i class="far calendar">&nbsp;<?=htmlspecialchars($date)?></i>
								<p class="preview-text">
									<?=$text?>
								</p>
								<a href="/article?id=<?=$post['id']?>" class="btn read-more">Подробнее</a>
							</div>
						</div>
						<?php
						endforeach;
						?>
					</div>
					<!-- Sidebar Content -->
					<div class="sidebar">
						<div class="section search">
							<h2 class="section-title">Поиск</h2>
							<form action="index.html">
								<input type="text" name="search-term" class="text-input" placeholder="Найти...">
							</form>
						</div>
						<div class="section topics">
							<h2 class="section-title">Тема</h2>
							<ul>
								<li><a href="/article?id=18">Обо мне</a></li>
								<li><a href="/article?id=4">Поддержка</a></li>
								<li><a href="#">Доноры Крови</a></li>
								<li><a href="/complaints">Жалобы и предложения</a></li>
								<li><a href="/agreement">Условия соглашения</a></li>
							</ul>
						</div>
					</div>
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
			<!-- Slider -->
			<script src="/public/scripts/slider.js"></script>
		</body>
	</html>