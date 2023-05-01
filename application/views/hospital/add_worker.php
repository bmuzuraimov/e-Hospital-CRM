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
    <title>Добавить Медперсонал</title>
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
          <h2 class="page-title">Добавить Мед-Работника</h2>
          <form action="/hospital/addw" method="post">
            <div>
              <label>Имя<span>*</span></label><br>
              <input type="text" name="fname" class="text-input" required/>
            </div>
            <div>
              <label>Фамилия<span>*</span></label><br>
              <input type="text" name="lname" class="text-input" required/>
            </div>
            <div>
              <label>Дата Рождения<span>*</span></label><br>
              <input type="date" name="birthday" class="text-input" required/>
            </div>
            <div>
              <label>Пол<span>*</span></label><br>
              <select name="sex" class="text-input">
                <option disabled selected></option>
                <option value="male" >Мужской</option>
                <option value="female">Женский</option>
              </select>
            </div>
            <div>
              <label>Номер телефона<span>*</span></label><br>
              <input type="tel" name="phone" placeholder="077XXXXXXX" class="text-input" required/>
            </div>
            <div>
              <label>Должность<span>*</span></label>
              <select name="position" class="text-input">
                <option disabled selected></option>
                <option value="Врач">Врач</option>
                <option value="Средний медперсонал">Средний медперсонал</option>
                <option value="Младший медперсонал">Младший медперсонал</option>
                <option value="Фармацевт">Фармацевт</option>
                <option value="Другой сотрудник">Другой сотрудник</option>
              </select>
            </div>
            <div class="animated-submit">
              <button type="submit" name="submit" class="btn btn-big" id="save-profile-btn">Добавить</button>
            </div>
          </form>
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