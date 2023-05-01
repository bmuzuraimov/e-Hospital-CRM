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
		<title>Монетизация</title>
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
				<div class="main-content-wrapper">
					<div class="main-content article">
						<h1 class="post-title">Поддержка приложения</h1>
						<div class="post-content">Элсом: 0773748984<br>DemirBank: Baiel Muzuraimov</div>
					</div>
				</div>
				<!-- //Main Content -->
				<!-- Sidebar Content -->
				<div class="sidebar article">
					<div class="section topics">
						<div class="section popular">
							<h2 class="section-title">Популярное</h2>
							<?php foreach ($popular_posts as $post): ?>
							<div class="post clearfix">
								<img src="<?=$post['profile_img']?>">
								<a class="title" href="/article?id=<?=$post['id']?>"><?=htmlspecialchars($post['title'])?></a>
							</div>
							<?php endforeach ?>
						</div>
					</div>
				</div>
				<!-- //Sidebar Content -->
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
	</body>
</html>