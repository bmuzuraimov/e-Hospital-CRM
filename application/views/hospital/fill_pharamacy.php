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
    <title>Пополнение Аптеки</title>
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
          <h2 class="page-title">Пополнение Аптеки</h2>
          <form action="/hospital/fillph" method="post" id="add-medicine-form">
            <span class="error">Внимение, не добавляйте лекарства не предназначенные для Covid-19</span><br><br>
            <div>
              <label>Наименование препарата<span>*</span></label><br>
              <input type="text" name="name" class="text-input" required/>
            </div>
            <div>
              <label>Преднозначение<span>*</span></label><br>
              <select name="purpose" class="text-input">
                <option disabled selected></option>
                <option value="neutralize" >Нейтрализация вируса</option>
                <option value="pneumonia">Пневмония</option>
                <option value="symptoms">Облегчение симптомов</option>
                <option value="immune">Активация иммунной системы</option>
                <option value="other">Другое</option>
              </select>
            </div>
            <div>
              <label>Предельная доза (мг)<span>*</span></label><br>
              <input type="number" name="maxdose" class="text-input">
            </div>
            <div>
              <label>Описание<span>*</span></label><br>
              <textarea rows="4" cols="80" name="description" class="text-input"></textarea>
            </div>
            <div class="animated-submit">
              <button type="submit" name="submit" class="btn btn-big" id="add-medicine-btn">Добавить</button>
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
        $("#add-medicine-btn").click(function(){
            $.post($("#add-medicine-form").attr("action"), $("#add-medicine-form :input").serializeArray(), function(info){
              $( "#add-medicine-btn" ).html("Добавлено");
            });
        });
        $("#add-medicine-form").submit(function(){
            return false;
        });
        $(".text-input").change(function(){
          $( "#add-medicine-btn" ).html("Добавить");
        });
      });      
    </script>
  </body>
</html>