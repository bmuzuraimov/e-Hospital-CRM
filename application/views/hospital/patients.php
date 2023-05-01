<?php
function date_to_age($dateofbirth){
	$today = date("Y-m-d");
	$diff = date_diff(date_create($dateofbirth), date_create($today));
	$diff = $diff->format('%y');
	return $diff;
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
		<link rel="stylesheet" href="/public/styles/hospital.css">
		<link rel="stylesheet" href="/public/styles/morris.css">
		<link rel="icon" href="/public/images/icons/icon_hospital.ico" type="image/icon">
		<title>Запросы</title>
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
					<div class="button-group" style="width: 100%;">
          				<a href="/hospital/units" class="btn  btn-big" style="float: right;"><i class="fas fa-campground"></i>Палаты</a>
        			</div>
					<div class="tab" style="grid-template-columns: 25% 25% 25% 25%;">
						<a href="?show=all">Запросы</a>
						<a href="?show=hospitalized">Госпитализованные</a>
						<a href="?show=que">В очереди</a>
						<a href="?show=recovered">Выписанные</a>
					</div>
		            <div>
		              <input type="text" name="patient_search" class="text-input" placeholder="Найти..." />
		            </div>
					<table id="requestTable">
						<?php if(empty($_GET)||$_GET["show"]=="all")
						{?>
						<tr>
							<th>Имя</th>
							<th>Фамилия</th>
							<th>Возраст</th>
							<th>Риск</th>
							<th>Просмотр</th>
							<th colspan="2">Дни</th>
						</tr>
						<?php
						foreach($waiting_list as $patient):?>
						<tr>
							<td><?=$patient['fname'];?></td>
							<td><?=$patient['lname'];?></td>
							<td><?=date_to_age($patient['age']);?></td>
							<td><?=$patient['risk'];?></td>
							<td>
								<a href="/hospital/viewp?patientid=<?=$patient["id"]?>" class="edit">Подробнее</a>
							</td>
							<td>
								<form action="/hospital/confirmp" method="post" onSubmit="return confirm('Подтвердите госпитализацию?');">
									<input type="number" class="text-input" name="days" value="2" id="days" min="2" max="30" step="2">
								</td>
								<?php if($analytics['aunits']>0){?>
								<td>
									<input type="hidden" name="type" value="confirm">
									<input type="hidden" name="patientid" value="<?=$patient['id'];?>"><button type="submit" class="btn">Потвердить</button></form>
								</td>
								<?php }else{?>
								<td>
									<input type="hidden" name="type" value="que">
									<input type="hidden" name="patientid" value="<?=$patient['id'];?>"><button type="submit" id="confirmButton">Очередь</button></form>
								</td>
								<?php }?>
							</tr>
							<?php
							endforeach;
							}
							//Table for Hospitalized Patients
							else if($_GET["show"]=="hospitalized"){?>
							<tr>
								<th>Имя</th>
								<th>Фамилия</th>
								<th>Возраст</th>
								<th>Риск</th>
								<th>Палата</th>
								<th colspan="2">Действие</th>
							</tr>
							<?php
							foreach($hospitalized_list as $patient):?>
							<tr>
								<td><?=$patient['fname'];?></td>
								<td><?=$patient['lname'];?></td>
								<td><?=date_to_age($patient['age']);?></td>
								<td><?=$patient['risk'];?></td>
								<td><?=$patient['unit'];?></td>
								<td>
									<a href="/hospital/viewp?patientid=<?=$patient["id"]?>" class="edit">Подробнее</a>
								</td>
								<td>
									<a href="/hospital/deleteu?patientid=<?=$patient["id"]?>&link=/hospital/patients?show=hospitalized" class="delete">Удалить</a>
								</td>
							</tr>
							<?php
							endforeach;
							}
							//The new table for patients in que
							else if($_GET["show"]=="que"){?>
							<tr>
								<th>Имя</th>
								<th>Фамилия</th>
								<th>Возраст</th>
								<th>Риск</th>
								<th>Просмотр</th>
								<th>Удалить</th>
							</tr>
							<?php
							foreach($que_list as $patient): ?>
							<tr>
								<td><?=$patient['fname'];?></td>
								<td><?=$patient['lname'];?></td>
								<td><?=date_to_age($patient['age']);?></td>
								<td><?=$patient['risk'];?></td>
								<td>
									<a href="/hospital/viewp?patientid=<?=$patient['patientid'];?>" class="publish">Подробнее</a>
								</td>
								<td>
									<a href="/hospital/deleteu?patientid=<?=$info['id'];?>&link=/hospital/patients?show=que" class="delete">Удалить</a>
								</td>
							</tr>
							<?php
							endforeach;
							}
							//The new table for recovered patients
							else if($_GET["show"]=="recovered"){?>
							<tr>
								<th>Имя</th>
								<th>Фамилия</th>
								<th colspan="2">Действие</th>
							</tr>
							<?php
							foreach($recovered_list as $patient):?>
							<tr>
								<td><?=$patient['fname'];?></td>
								<td><?=$patient['lname'];?></td>
								<td>
									<a href="/hospital/viewp?patientid=<?=$patient["id"]?>" class="publish">Подробнее</a>
								</td>
								<td>
									<?php
									if ($patient['status']=="hospitalized") {?>
									<a href="/hospital/viewp?patientid=<?=$patient["id"]?>&link=/hospital/patients?show=recovered" class="edit">Выписка</a>
									<?php
									}else{?>
									<a href="#" class="edit">Здоров</a>
									<?php
									} ?>
								</td>
							</tr>
							<?php
							endforeach;
							}?>
						</table>
				</div>
			</div>
			<!--//Hospital Content-->
		</div>
		<!-- //Page Wrapper -->
		<!-- JQuery -->
		<script type="text/javascript" src="/public/scripts/jquery.js"></script>
		<!-- Custom -->
		<script type="text/javascript" src="/public/scripts/scripts.js"></script>
		<script type="text/javascript">
			$(".tab a").click(function(){
				$('.tab a').eq($(this).index()).addClass('selected-tab').siblings().removeClass('selected-tab');
			 });
		</script>
	</body>
</html>