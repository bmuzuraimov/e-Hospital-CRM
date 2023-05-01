<?php
    function get_patient_image($age, $gender, $improve = true)
    {
        if ($gender == 'male') {
            switch ($age) {
                case ($age >= 0 && $age < 12):
                    if ($improve) {
                        return '/public/images/patient_profile/posmchild.png';
                    } else {
                        return '/public/images/patient_profile/negmchild.png';
                    }
                    break;
                case ($age >= 12 && $age < 50):
                    if ($improve) {
                        return '/public/images/patient_profile/posmperson.png';
                    } else {
                        return '/public/images/patient_profile/negmperson.png';
                    }
                    break;
                case ($age >= 50):
                    if ($improve) {
                        return '/public/images/patient_profile/posmold.png';
                    } else {
                        return '/public/images/patient_profile/negmold.png';
                    }
                    break;
                default:
                    return '/public/images/patient_profile/posmperson.png';
                    break;
            }
        } else {
            switch ($age) {
                case ($age >= 0 && $age < 12):
                    if ($improve) {
                        return '/public/images/patient_profile/posfchild.png';
                    } else {
                        return '/public/images/patient_profile/negfchild.png';
                    }
                    break;
                case ($age >= 12 && $age < 50):
                    if ($improve) {
                        return '/public/images/patient_profile/posfperson.png';
                    } else {
                        return '/public/images/patient_profile/negfperson.png';
                    }
                    break;
                case ($age >= 50):
                    if ($improve) {
                        return '/public/images/patient_profile/posfold.png';
                    } else {
                        return '/public/images/patient_profile/negfold.png';
                    }
                    break;
                default:
                    return '/public/images/patient_profile/posfperson.png';
                    break;
            }
        }
    }
function date_convert($date){
  $months = ["01" => "янв.", "02" => "фев.", "03" => "мар.", "04" => "апр.", "05" => "мая", "06" => "июн.", "07" => "июл.", "08" => "авг.", "09" => "сен.", "10" => "окт.", "11" => "ноя.", "12" => "дек."];
  $month = mb_substr($date, 5, 2);
  $month = $months[$month];
  $day = mb_substr($date, 8, 2);
  $day = (mb_substr($day, 0, 1)==0) ? mb_substr($day, 1, 1) : $day;
  return $day." ".$month;
}
function date_to_age($dateofbirth){
	$today = date("Y-m-d");
	$diff = date_diff(date_create($dateofbirth), date_create($today));
	$diff = $diff->format('%y');
	return $diff;
}
function getTotal($fromDate, $toDate){
	if(isset($toDate)){
		$toDate = date("Y-m-d");
	}
	$days = date_diff(date_create($toDate), date_create($fromDate));
	$days = $days->format('%d');
	return $days;
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
		<link rel="stylesheet" type="text/css" href="/public/styles/units.css">
		<link rel="icon" href="/public/images/icons/icon_doctor.ico" type="image/icon">
		<title>Палаты</title>
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
					<h2 class="page-title">Палаты</h2>
					<div class="cards">
					<?php
					foreach($patients as $patient):
					$age = date_to_age($patient['age']);
					$profile = get_patient_image($age, $patient['sex']);
					?>
					<div class="unit occupied-unit">
		              <div class="patient">
		                <img src="<?=$profile;?>" style="width:100%">
		                <h3>Палата №<?=$patient['unit'];?></h3>
		                <a href="/doctor/treatment?patientid=<?=$patient['id'];?>" class="patient-name"><?=$patient['fname']." ".$patient['lname'];?></a>
		                <p><?="От ".date_convert($patient['fromDate'])." до ".date_convert($patient['toDate']);?></p>
		                <p>Риск на летальность: <?=$patient['risk'];?>%</p>
		                <input type="hidden" id="patientid<?=$num_graph;?>" value="<?php echo $patient['id'];?>">
		              </div>
					</div>
					<?php
					endforeach;
					?>
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
	</body>
</html>
