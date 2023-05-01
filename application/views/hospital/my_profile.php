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
    <link rel="stylesheet" type="text/css" href="/public/styles/profile.css">
    <link rel="stylesheet" href="/public/styles/morris.css">
    <link rel="icon" href="/public/images/icons/icon_hospital.ico" type="image/icon">
    <title>Профиль</title>
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
        <div class="button-group">
          <a href="/hospital/editprofile" class="btn  btn-big">Редактировать</a>
        </div>
          <div class="wrapper">
            <div class="hospital-left">
              <?php
              if($my_info['sex']=="Мужской"){ ?>
              <img src="/public/images/doctor_profile/head.png" alt="male worker">
              <?php
              }else{?>
              <img src="/public/images/doctor_profile/head.png" alt="female worker">
              <?php
              } ?>
              <h4 class="page-title"><?=$my_info['fname']." ".$my_info['lname']?></h4>
              <a href="#">ИНН: <?=$my_info['passport']?></a>
              <p>Главный врач</p>
            </div>
            <div class="right">
              <div class="info">
                <h3><?=$my_info['name'];?></h3>
                <div class="info-data">
                  <div class="data">
                    <h4>Адрес</h4>
                    <p><?=$my_info['address'];?></p>
                  </div>
                  <div class="data">
                    <h4>Город</h4>
                    <p><?=$my_info['city'];?></p>
                  </div>
                  <div class="data">
                    <h4>Всего коек</h4>
                    <p><?=$my_info['tunits'];?></p>
                  </div>
                </div>
              </div>
              
              <div class="extra-info">
                <div class="extra-info-data">
                  <div class="data">
                    <h4>Номер телефона</h4>
                    <p><?=$my_info['phone'];?></p>
                  </div>
                  <div class="data">
                    <h4>Дата рождения</h4>
                    <p><?=$my_info['birthday'];?></p>
                  </div>
                  <div class="data">
                    <h4>Дата регистрации</h4>
                    <p><?=$my_info['logDate'];?></p>
                  </div>
                </div>
              </div>
              <div class="extra-info">
                <h3>Достижения</h3>
                <div class="extra-info-data">
                  <div class="data">
                    <h4>Зарегистрировано</h4>
                    <p><?=$total_number;?></p>
                  </div>
                  <div class="data">
                    <h4>Госпитализирвано</h4>
                    <p><?=$recovered_number?></p>
                  </div>
                  <div class="data">
                    <h4>Вылеченные</h4>
                    <p><?=$recovered_number?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--//Hospital Content-->
    </div>
    <!-- //Page Wrapper -->
    <!-- Custom -->
    <script src="/public/scripts/scripts.js"></script>
  </body>
</html>