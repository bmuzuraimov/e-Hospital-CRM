<?php
use application\models\Hospital;
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
  return $day." ".$month;
}
function date_to_age($dateofbirth){
$today = date("Y-m-d");
$diff = date_diff(date_create($dateofbirth), date_create($today));
$diff = $diff->format('%y');
return $diff;
}
$num_graph = 1;
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
    <link rel="stylesheet" type="text/css" href="/public/styles/units.css">
    <link rel="stylesheet" href="/public/styles/morris.css">
    <link rel="icon" href="/public/images/icons/icon_unit.ico" type="image/icon">
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
          <h2 class="page-title">Койки</h2>
          <div class="cards">
            <?php
            foreach ($occupied_units as $unit):
            $fromDate = date_convert($unit['fromDate']);
            $toDate = date_convert($unit['toDate']);
            $time = substr($unit['time'], 0,-3);
            $patient_condition = Hospital::get_health($unit['patientid'], $hospitalid);
            $improve = true;
            $today = null;
            $sum = 0;
            $patient_condition_days = 0;
            if ($patient_condition) {
            $patient_condition_days = count($patient_condition);
            for($i = 0; $i<($patient_condition_days-1); $i++){
            $greater = max($patient_condition[$i]['improvement'],$patient_condition[$i+1]['improvement']);
            $smaller  = min($patient_condition[$i]['improvement'],$patient_condition[$i+1]['improvement']);
            $trian = $greater-$smaller;
            $area = $smaller+($trian*.5);
            $sum = $sum+$area;
            }
            $improvement = array_slice($patient_condition, -2, 2);
            if(count($improvement)>1){
            $yesterday = $improvement[0]['improvement'];
            $today = $improvement[1]['improvement'];
            if ($today>$yesterday) {
            $improve = true;
            }else{
            $improve = false;
            }
            }
            }
            $age = date_to_age($unit['age']);
            $profile = get_patient_image($age, $unit['sex'], $improve);?>
            <div class="unit occupied-unit">
              <div class="patient" id="profile<?php echo $num_graph;?>">
                <img src="<?=$profile;?>" style="width:100%">
                <h3>Палата №<?=$unit['unit'];?></h3>
                <a href="/hospital/viewp?patientid=<?=$unit["patientid"]?>" class="patient-name"><?=$unit['fname']." ".$unit['lname'];?></a>
                <p><?="От $fromDate до $toDate";?></p>
                <p>Риск на летальность: <?=$unit['risk']?>%</p>
                <input type="hidden" id="patientid<?=$num_graph;?>" value="<?php echo $unit['id'];?>">
              </div>
              <div class="patient-graph" id="graph<?php echo $num_graph;?>">
                <div id="patgraph<?=$unit['patientid'];?>" style="width: 300px;height: 340px;"></div>
                <h3>Сведения</h3>
                <p>Общее состояние: <?=($sum!=0 ? number_format($sum, 1) : '');?></p>
                <p>Среднее: <?=($patient_condition_days != 0 ? number_format(($sum/$patient_condition_days), 1) : '');?></p>
                <p>Последнее: <?=(isset($today) ? $today : '');?></p>
              </div>
              <a class="graph-btn" id="btn<?=$num_graph;?>">Graph</a>
              <a href="/hospital/deleteu?patientid=<?=$unit["patientid"]?>&link=/hospital/units" class="delete-btn">Удалить</a>
              <div class="que-dropdown">
                <a href="/hospital/patients?show=all" class="que-btn">Очередь</a>
                <div class="que-list">
                  <?php
                  $que_units = Hospital::get_ques_by_unit($hospitalid,$unit['unit']);
                  if($que_units==false){?>
                  <a class="no-que">Нет очереди</a>
                  <?php
                  }else{
                  foreach($que_units as $que_unit): ?>
                  <a href="/hospital/viewp?patientid=<?=$que_unit['id'];?>"><?=$que_unit['fname']." ".$que_unit['lname']?></a>
                  <?php
                  endforeach;
                  }?>
                </div>
              </div>
            </div>
            <?php
            $num_graph++;
            endforeach;
            for($i=0;$i<count($empty_units);$i++):
            $last_patient = Hospital::last_patient_of_unit($empty_units[$i], $hospitalid);
            ?>
            <div class="unit empty-unit">
              <img src="/public/images/available.jpg" style="width:100%">
              <h3><?="Палата №$empty_units[$i]";?></h3>
              <a class="available-name">Доступная</a>
              <p>Последний пациент:</p>
              <?php if ($last_patient != false) { ?>
              <a href="/hospital/viewp?patientid=<?=$last_patient["id"]?>" class="publish"><?=$last_patient['fname']." ".$last_patient['lname'];?></a>
              <?php } else { ?>
              <p>Нету Данных</p>
              <?php
              }
              ?>
              <div class="add-dropdown">
                <input type="number" onKeyup="updateTextInput(this.value);" required>
                <div class="add-list">
                  <?php
                  if($diagnosed_patients==false){?>
                    <a class="no-que">Ничего не найдено</a>
                  <?php
                  }else{
                  foreach($diagnosed_patients as $patient):
                  ?>
                  <form class="form-inline" action="/hospital/confirmp" method="post" onSubmit="return confirm('Подтвердите госпитализацию?');">
                    <input type="hidden" name="type" value="unit">
                    <input type="hidden" name="unit" value="<?=$empty_units[$i];?>">
                    <input type="hidden" class="day" name="days" min="2" max="30">
                    <input type="hidden" name="patientid" value="<?=$patient['id'];?>"/>
                    <button class="list" type="submit"><?=$patient['fname']." ".$patient['lname'];?></button>
                  </form>
                  <?php
                  endforeach;}?>
                </div>
              </div>
            </div>
            <?php
            endfor;?>
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
    <!-- Morris -->
    <script src="/public/scripts/morris.js"></script>
    <!-- Raphael-min -->
    <script src="/public/scripts/raphael-min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
    <?php
    foreach ($occupied_units as $unit) {?>
    Morris.Area({
    element: 'patgraph'+<?php echo $unit['patientid'];?>,
    redraw: true,
    behaveLikeLine: true,
    data: [
    <?php
    $patient_condition = Hospital::get_health($unit['patientid'], $hospitalid);
    foreach ($patient_condition as $graph){
    echo "{date: '".$graph['data']."', y: '".$graph['improvement']."'},";
    }?>
    ],
    xkey: 'date',
    ykeys: 'y',
    labels: ['состояние', 'Y']
    });
    <?php } ?>
    });
    $('.graph-btn').click(function() {
    var id = $(this).attr('id');
    id = id.substring(3, 4);
    var patientid = $('#patientid'+id).val();
    var lable = $('#btn' + id + '.graph-btn').text().trim();
    if(lable == "Graph") {
    $('#btn' + id + '.graph-btn').text("Close");
    $('#profile' + id + '.patient').hide();
    $('#graph' + id + '.patient-graph').show();
    }
    else {
    $('#btn' + id + '.graph-btn').text("Graph");
    $('#graph' + id + '.patient-graph').hide();
    $('#profile' + id + '.patient').show();
    }
    });
    function updateTextInput(val)
    {
    var len = document.querySelectorAll('.day').length;
    for (i = 0; i < len; i++) {
    document.getElementsByClassName('day')[i].setAttribute("value", val);
    }
    }
    </script>
  </body>
</html>