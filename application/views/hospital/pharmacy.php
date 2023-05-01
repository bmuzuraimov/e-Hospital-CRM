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
    <link rel="icon" href="/public/images/icons/icon_hospital.ico" type="image/icon">
    <title>Аптека</title>
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
          <h2 class="page-title">Аптека</h2>
          <div class="menu">
            <button onclick="window.location.href = '/hospital/fillph';">Добавить</button>
            <div class="tab" style="grid-template-columns: auto auto auto auto auto;">
              <a href="?medicine=all">Все</a>
              <a href="?medicine=neutralize">Нейтрализация вируса</a>
              <a href="?medicine=symptoms">Облегчение симптомов</a>
              <a href="?medicine=immune">Активация имунной системы</a>
              <a href="?medicine=others">Другое</a>
            </div>
            <div>
              <input type="text" name="medicine_search" class="text-input" placeholder="Найти..." />
            </div>
          </div>
          <div class="cards">
            <?php
            $purposes = ["neutralize" => "нейтрализации вируса", "symptoms" => "облегчения симптомов", "immune" => "активации имунной системы", "pneumonia" => "пневмонии", "others" => "другое"];
            foreach($drugs as $drug){
              $purpose = $purposes[$drug['purpose']];
              $link = "#doitlater";
            ?>
            <div class="card">
              <img src="/public/images/medicine.png" alt="Лекарство">
              <h4><a href=<?=$link?>><?=$drug['name'];?></a></h4>
              <div class="card-info">
                <p>Для <?=$purpose;?></p>
                <p>Эфективность: <?=$drug['efficiency'];?>%</p>
                <p>Макс. доза: <?=$drug['maxdose'];?>мг</p>
              </div>
            </div>
            <?php }?>
          </div>
        </div>
      </div>
      <!--//Hospital Content-->
    </div>
    <!-- //Page Wrapper -->
    <!-- JQuery -->
    <script type="text/javascript" src="/public/scripts/jquery.js"></script>
    <!-- Custom -->
    <script src="/public/scripts/scripts.js"></script>
  </body>
</html>