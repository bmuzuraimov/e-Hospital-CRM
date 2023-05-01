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
    <link rel="icon" href="/public/images/icons/icon_doctor.ico" type="image/icon">
    <title>Редактирвать профиль</title>
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
          <h2 class="page-title">Редактирвать Профиль</h2>
          <form action="/doctor/editprofile" method="post" id="save-profile-form">
            <div>
              <label>Имя<span>*</span></label><br>
              <input type="text" class="text-input" name="fname" value="<?=$my_info['fname'];?>"/>
            </div>
            <div>
              <label>Фамилия<span>*</span></label><br>
              <input type="text" class="text-input" name="lname" value="<?=$my_info['lname'];?>"/>
            </div>
            <div>
              <label>ИНН<span>*</span></label><br>
              <input type="number" class="text-input" name="passport" value="<?=$my_info['passport'];?>"/>
            </div>
            <div>
              <label>Дата Рождения<span>*</span></label><br>
              <input type="date" class="text-input" name="birthday" value="<?=$my_info['birthday'];?>"/>
            </div>
            <div>
              <label>Должность<span>*</span></label><br>
              <select name="position" class="text-input">
                <option value="Врач" <?php if ($my_info['position'] == 'Врач' ) echo 'selected' ;?>>Врач</option>
                <option value="Средний медперсонал" <?php if ($my_info['position'] == 'Средний медперсонал' ) echo 'selected' ;?>>Средний медперсонал</option>
                <option value="Младший медперсонал" <?php if ($my_info['position'] == 'Младший медперсонал' ) echo 'selected' ;?>>Младший медперсонал</option>
                <option value="Фармацевт" <?php if ($my_info['position'] == 'Фармацевт' ) echo 'selected' ;?>>Фармацевт</option>
                <option value="Другой сотрудник" <?php if ($my_info['position'] == 'Другой сотрудник' ) echo 'selected' ;?>>Другой сотрудник</option>
              </select>
            </div>
            <div>
              <label>Пол<span>*</span></label><br>
              <select name="sex" class="text-input">
                <option value="Мужской" <?php if ($my_info['sex']=="Мужской") echo "selected"; ?>>Мужской</option>
                <option value="Женский" <?php if ($my_info['sex']=="Женский") echo "selected"; ?>>Женский</option>
              </select>
            </div>
            <div class="animated-submit">
              <button type="submit" name="submit" class="btn btn-big" id="save-profile-btn">Сохранить</button>
            </div>
          </form>
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