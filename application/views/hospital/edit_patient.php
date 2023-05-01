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
    <title>Редактировать пациента</title>
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
          <?php if ($isEditable==true) { ?>
            <h2 class="page-title">Редактировать Инфо</h2>
          <?php } else { ?>
            <h3 class="page-title msg error"><i class="fas fa-exclamation-circle"></i>&nbsp;Нельзя редактировать данные пациентов в архиве!</h3>
          <?php } ?>
          <form action="/hospital/updatep" method="post" id="save-patient-form">
            <input type="hidden" name="id" class="text-input" value="<?=$patient['id'];?>">
            <div>
              <label>Имя</label>
              <input type="text" name="fname" value = "<?=$patient['fname'];?>" class="text-input" required/>
            </div>
            <div>
              <label>Фамилия</label>
              <input type="text" name="lname" value = "<?=$patient['lname'];?>" class="text-input" required/>
            </div>
            <div>
              <label>Возраст</label>
              <input type="date" name="bday" value = "<?=$patient['age'];?>" class="text-input" required/>
            </div>
            <div>
              <label>Пол</label>
              <select name="sex" class="text-input">
                <option <?php if ($patient['sex'] == 'male' ) echo 'selected' ;?> value="male">Мужской</option>
                <option <?php if ($patient['sex'] == 'female' ) echo 'selected' ;?> value="female">Женский</option>
              </select>
            </div>
            <div>
              <label>Телефон</label>
              <input type="tel" name="phone" value = "<?=$patient['emcontact'];?>" class="text-input" required/>
            </div>
            <div>
              <label>Симптомы</label>
              <textarea rows="4" cols="50" name="symptoms" class="text-input"><?=$patient['symptoms'];?>
              </textarea>
            </div>
            <?php
            if ($isEditable===true) { ?>
            <div class="animated-submit">
              <button type="submit" name="submit" class="btn btn-big" id="save-patient-btn">Сохранить</button>
            </div>
            <?php } ?>
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
    <script>
    var isEditable = "<?=$isEditable?>";
    $(document).ready(function() {
        var isEditable = "<?=$isEditable?>";
        if (!isEditable) {
          $("input, select, textarea").attr("disabled", true);
        }else{
          $("#save-patient-btn").click(function(){
              $.post($("#save-patient-form").attr("action"), $("#save-patient-form :input").serializeArray(), function(info){
                $( "#save-patient-btn" ).html("Сохранено");
              });
          });
          $("#save-patient-form").submit(function(){
              return false;
          });
          $(".text-input").change(function(){
            $( "#save-patient-btn" ).html("Сохранить");
          });
        }
      });
    </script>
  </body>
</html>