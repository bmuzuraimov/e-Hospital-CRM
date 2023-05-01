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
    <title>Настройка профиля</title>
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
          if ($doctor_info['fname']!=false && $doctor_info['lname']!=false) { ?>
            <a href="/hospital"><?=$doctor_info['fname']." ".$doctor_info['lname']?><i class="fa fa-chevron-down"></i></a>
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
          <h2 class="page-title">Настройка Профиля</h2>
          <form action="/hospital/editprofile" method="post" id="save-profile-form">
            <div>
              <label>Название больницы<span>*</span></label><br>
              <input type="text" name="name" value="<?=$doctor_info['name'];?>" class="text-input" required/>
            </div>
            <div>
              <label>Адрес<span>*</span></label><br>
              <input type="text" name="address" value="<?=$doctor_info['address'];?>" class="text-input" required/>
            </div>
            <div>
              <label>Город<span>*</span></label><br>
              <input type="text" name="city" value="<?=$doctor_info['city'];?>" class="text-input" required/>
            </div>
            <div>
              <label>Область<span>*</span></label><br>
              <select name="oblast" class="text-input">
                <option <?php if ($doctor_info['oblast'] == 'Баткенская область' ) echo 'selected';?> value="Баткенская область">Баткенская область</option>
                <option <?php if ($doctor_info['oblast'] == 'Джалал-Абадская область' ) echo 'selected';?> value="Джалал-Абадская область">Джалал-Абадская область</option>
                <option <?php if ($doctor_info['oblast'] == 'Иссык-Кульская область' ) echo 'selected';?> value="Иссык-Кульская область">Иссык-Кульская область</option>
                <option <?php if ($doctor_info['oblast'] == 'Нарынская область' ) echo 'selected';?> value="Нарынская область">Нарынская область</option>
                <option <?php if ($doctor_info['oblast'] == 'Ошская область' ) echo 'selected';?> value="Ошская область">Ошская область</option>
                <option <?php if ($doctor_info['oblast'] == 'Таласская область' ) echo 'selected';?> value="Таласская область">Таласская область</option>
                <option <?php if ($doctor_info['oblast'] == 'Чуйская область' ) echo 'selected';?> value="Чуйская область">Чуйская область</option>
              </select>
            </div>
            <div>
              <label>Всего коек<span>*</span></label><br>
              <input type="number" name="tunits" value="<?=$doctor_info['tunits'];?>" class="text-input" required/>
            </div>
            <div>
              <label>Должность<span>*</span></label><br>
              <input type="text" name="position" value="Главный врач" class="text-input" disabled />
            </div>
            <div>
              <label>Имя<span>*</span></label><br>
              <input type="text" name="fname" value="<?=$doctor_info['fname'];?>" class="text-input"/>
            </div>
            <div>
              <label>ИНН<span>*</span></label><br>
              <input type="text" name="passport" value="<?=$doctor_info['passport'];?>" class="text-input"/>
            </div>
            <div>
              <label>Фамилия<span>*</span></label><br>
              <input type="text" name="lname" value="<?=$doctor_info['lname'];?>" class="text-input"/>
            </div>
            <div>
              <label>Дата Рождения<span>*</span></label><br>
              <input type="date" name="birthday" value="<?=$doctor_info['birthday'];?>" class="text-input"/>
            </div>
            <div>
              <label>Пол<span>*</span></label><br>
              <select name="sex" class="text-input">
                  <option <?php if ($doctor_info['sex']=="Мужской") echo 'selected';?> value="Мужской">Мужской</option>
                  <option <?php if ($doctor_info['sex']=="Женский") echo 'selected';?> value="Женский">Женский</option>
              </select>
            </div>
            <div class="animated-submit">
              <button type="submit" name="submit" class="btn btn-big" id="save-profile-btn">Сохранить</button>
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
    <script type="text/javascript">
    $(document).ready(function() {
        $("#save-profile-btn").click(function(){
            $.post($("#save-profile-form").attr("action"), $("#save-profile-form :input").serializeArray(), function(info){
              $( "#save-profile-btn" ).html("Сохранено");
            });
        });
        $("#save-profile-form").submit(function(){
            return false;
        });
        $(".text-input").change(function(){
          $( "#save-profile-btn" ).html("Сохранить");
        });
      });      
    </script>
  </body>
</html>