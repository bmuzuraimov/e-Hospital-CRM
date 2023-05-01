<?php
namespace application\controllers;

use application\core\Controller;

if (isset($_SESSION['user'])) {
    $GLOBALS['$ID'] = $_SESSION['user'];
} else {
    $GLOBALS['$ID'] = false;
}
class DoctorController extends Controller
{
    protected $id;
    const SERVICE      = 100;
    const ILLNESSRISK  = 0.3;
    const AGERISK      = 0.25;
    const GENDERRISK   = 0.05;
    const BREATHRISK   = 0.25;
    const TEMPRISK     = 0.1;
    const WEAKNESSRISK = 0.05;
    public function setId()
    {
        if ($GLOBALS['$ID'] === false) {
            $this->model->delete_key($this->id, self::SERVICE);
            session_destroy();
            $this->view->redirect('/');
        } else {
            $this->id = $GLOBALS['$ID'];
        }
    }
    private function isLogin()
    {
        $this->setId();
        $auth = $this->model->get_key($this->id, self::SERVICE);
        if ($this->id != $auth['userid'] || $_SESSION['authkey'] != $auth['authKey'] || $auth['service'] != self::SERVICE) {
            $this->model->delete_key($this->id, self::SERVICE);
            session_destroy();
            $this->view->redirect('/');
        }
    }
    /*private function make_sentence($symptoms)
    {
    $lenth    = count($symptoms);
    $sentence = "";
    if (empty($symptoms)) {
    $symptoms = "нет симптомов";
    } else {
    for ($i = 0; $i < $lenth; $i++) {
    if ($i == ($lenth - 1)) {
    $sentence = $sentence . $symptoms[$i] . ".";
    } else {
    $sentence = $sentence . $symptoms[$i] . ", ";
    }
    }
    }
    return $sentence;
    }*/
    private function date_convert($date, $short = false)
    {
        if ($short === false) {
            $months         = ["01" => "Января", "02" => "Февраля", "03" => "Марта", "04" => "Апреля", "05" => "Мая", "06" => "Июня", "07" => "Июля", "08" => "Августа", "09" => "Сентября", "10" => "Октября", "11" => "Ноября", "12" => "Декабря"];
            $year           = mb_substr($date, 0, 4);
            $month          = mb_substr($date, 5, 2);
            $month          = $months[$month];
            $day            = mb_substr($date, 8, 2);
            $day            = (mb_substr($day, 0, 1) == 0) ? mb_substr($day, 1, 1) : $day;
            $converted_date = $day . " " . $month . ", " . $year;
            return $converted_date;
        } else {
            $months         = ["01" => "янв", "02" => "фев", "03" => "мар", "04" => "апр", "05" => "мая", "06" => "июн", "07" => "июл", "08" => "авг", "09" => "сен", "10" => "окт", "11" => "ноя", "12" => "дек"];
            $year           = mb_substr($date, 0, 4);
            $month          = mb_substr($date, 5, 2);
            $month          = $months[$month];
            $day            = mb_substr($date, 8, 2);
            $day            = (mb_substr($day, 0, 1) == 0) ? mb_substr($day, 1, 1) : $day;
            $time           = mb_substr($date, 10, 6);
            $converted_date = $time . ", " . $day . " " . $month;
            return $converted_date;
        }
    }
    private function check_symptoms($symptoms)
    {
        $symptoms = explode(",", $symptoms);
        $length   = count($symptoms);
        if ($length != 0) {
            for ($i = 0; $i < $length; $i++) {
                $isSymptomExists = $this->model->is_symptome_exists($symptoms[$i]);
                if (is_null($isSymptomExists) && $symptoms[$i] != '') {
                    $this->model->add_symptome($symptoms[$i]);
                }
            }
            for ($j = 0; $j < $length; $j++) {
                $this->model->use_symptome($symptoms[$j]);
            }
        }
    }
    public function add_patientAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        $analytics  = $this->model->get_analytics($hospitalid);
        $symptoms   = $this->model->get_symptoms();
        $vars       = [
            'analytics'  => $analytics,
            'username'   => $username,
            'symptoms'   => $symptoms,
            'hospitalid' => $hospitalid,
        ];
        $this->view->render($vars);
    }
    public function addpatientAction()
    {
        if (isset($_POST['fname'])) {
            $hospitalid  = filter_input(INPUT_POST, 'hospitalid');
            $first_name  = filter_input(INPUT_POST, 'fname');
            $last_name   = filter_input(INPUT_POST, 'lname');
            $dateOfBirth = filter_input(INPUT_POST, 'age');
            $sex         = filter_input(INPUT_POST, 'sex');
            $passport    = filter_input(INPUT_POST, 'passport');
            $phone       = filter_input(INPUT_POST, 'phone');
            $symptoms    = filter_input(INPUT_POST, 'symptoms');
            $this->check_symptoms($symptoms);
            $patientid = $this->model->add_patient($hospitalid, $first_name, $last_name, $dateOfBirth, $sex, $passport, $phone, $symptoms);
            echo "<h3 class=\"page-title msg success\">Пациент добавлен! <a  href=\"/doctor/diagnostic?patientid=$patientid\">Диагностика пациента " . $last_name . " " . $first_name . "</a>&nbsp;<i class=\"fas fa-arrow-right\"></i></h3>";
        } else {
            echo "<h3 class=\"page-title msg error\">Произошла ошибка!</h3>";
        }
    }
    public function chatAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        $analytics  = $this->model->get_analytics($hospitalid);
        $limit      = 15;
        $message    = filter_input(INPUT_POST, 'message');
        if (isset($message)) {
            $this->model->send_message($hospitalid, $this->id, self::SERVICE, $message);
            $chat = $this->model->get_myhospital_messages($hospitalid);
        }
        $vars = [
            'analytics' => $analytics,
            'username'  => $username,
        ];
        $this->view->render($vars);
    }
    public function get_messagesAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        $chat       = $this->model->get_myhospital_messages($hospitalid);
        $userid     = $this->id;
        foreach ($chat as $message):
            if ($message['userid'] == $userid) {
                ?>
                                                <div class="chat-message">
                                                    <div class="chat self">
                                                        <div class="user-photo"><img src="/public/images/doctor_profile/male.png" alt="male worker"></div>
                                                        <div class="message">
                                                            <p><?php echo $message['message']; ?></p>
                                                            <span class="time-left"><?=$this->date_convert($message['date'], true);?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                    <?php
    } else if ($message['isHead'] == 103) {
            ?>
                    <div class="chat-message">
                        <div class="chat doctor">
                            <div class="user-photo"><img src="/public/images/doctor_profile/head.png" alt="head physician"></div>
                            <div class="message">
                                <h6 class="sender"><?php echo $message['sender']; ?></h6>
                                <p><?php echo $message['message']; ?></p>
                                <span class="time-right"><?=$this->date_convert($message['date'], true);?></span>
                            </div>
                        </div>
                    <div class="chat-message">
                    <?php
} else {
            ?>
            <div class="chat-message">
                <div class="chat doctor">
                    <div class="user-photo"><img src="/public/images/doctor_profile/male.png" alt="male worker"></div>
                    <div class="message">
                        <h6 class="sender"><?php echo $message['sender']; ?></h6>
                        <p><?php echo $message['message']; ?></p>
                        <span class="time-right"><?=$this->date_convert($message['date'], true);?></span>
                    </div>
                </div>
            </div>
            <?php
}
        endforeach;
    }
    public function fill_pharamacyAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        $analytics  = $this->model->get_analytics($hospitalid);
        if (isset($_POST['submit'])) {
            $name        = filter_input(INPUT_POST, 'name');
            $purpose     = filter_input(INPUT_POST, 'purpose');
            $maxdose     = filter_input(INPUT_POST, 'maxdose');
            $description = filter_input(INPUT_POST, 'description');
            $this->model->set_drug($name, $purpose, $maxdose, $description);
            $this->view->redirect('/doctor/pharmacy');
        }
        $vars = [
            'analytics' => $analytics,
            'username'  => $username,
        ];
        $this->view->render($vars);
    }
    public function diagnosticAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        $analytics  = $this->model->get_analytics($hospitalid);
        $patientid  = filter_input(INPUT_GET, 'patientid');
        if (isset($patientid)) {
            $patientinfo = $this->model->get_patient_info($patientid, $hospitalid);
            $isChecked   = $this->model->risk_exists($patientid);
            if ($patientinfo != false && !$isChecked) {
                $name        = $patientinfo['fname'] . " " . $patientinfo['lname'];
                $dateOfBirth = $patientinfo['age'];
                $age         = $this->model->date_to_age($dateOfBirth);
                $age         = $this->model->get_age_range($age);
                $sex         = $this->model->get_sex($patientinfo['sex']);
                $vars        = [
                    'analytics' => $analytics,
                    'username'  => $username,
                    'patientid' => $patientid,
                    'name'      => $name,
                    'age'       => $age,
                    'sex'       => $sex,
                ];
                $this->view->render($vars);
            } else {
                $this->view->redirect('/doctor/patients');
            }
        } else {
            $this->view->redirect('/doctor/patients');
        }
    }
    public function treatmentAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        $analytics  = $this->model->get_analytics($hospitalid);
        $patientid  = filter_input(INPUT_GET, 'patientid');
        if (isset($patientid)) {
            $patient = $this->model->get_patient_info($patientid, $hospitalid);
            if ($patient != false) {
                $unit_info = $this->model->get_unit_info($patientid, $hospitalid);
                $today     = date("Y-m-d");
                $day       = date_diff(date_create($unit_info['fromDate']), date_create($today));
                $day       = $day->format('%d');
                $day++;
                $plan_treatment = $this->model->get_plan_treatments($patientid, $hospitalid, $day);
                $isChecked      = $this->model->get_checked_patient($patientid, $hospitalid);
                $isChecked      = ($isChecked !== false) ? false : true;
                $vars           = [
                    'analytics'      => $analytics,
                    'username'       => $username,
                    'patientid'      => $patientid,
                    'patient'        => $patient,
                    'plan_treatment' => $plan_treatment,
                    'isChecked'      => $isChecked,
                ];
                $this->view->render($vars);
            } else {
                $this->view->redirect('/doctor/units');
            }
        } else {
            $this->view->redirect('/doctor/units');
        }
    }
    public function search_medicineAction()
    {
        $this->isLogin();
        $medicine = filter_input(INPUT_GET, 'medicine');
        if (isset($medicine)) {
            $medicine_list = $this->model->get_drug($medicine, null);
            if (isset($medicine_list)) {
                foreach ($medicine_list as $suggest) {
                    echo "<option value='" . $suggest['name'] . "'>";
                }
            }
        }
    }
    public function update_treatmentAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        if (isset($_POST['patientid'])) {
            $patientid           = filter_input(INPUT_POST, 'patientid');
            $neutralize_medicine = filter_input(INPUT_POST, 'nmedicine');
            $neutralize_dose     = filter_input(INPUT_POST, 'ndose');
            if ($neutralize_medicine != "") {
                $isExist = $this->model->get_drug($neutralize_medicine, "neutralize", true);
                if (!empty($isExist)) {
                    $this->model->set_treatment($patientid, $hospitalid, $isExist['id'], $neutralize_dose);
                }
            }
            $symptoms_medicine = filter_input(INPUT_POST, 'smedicine');
            $symptoms_dose     = filter_input(INPUT_POST, 'sdose');
            if ($symptoms_medicine != "") {
                $isExist = $this->model->get_drug($symptoms_medicine, "symptoms", true);
                if (!empty($isExist)) {
                    $this->model->set_treatment($patientid, $hospitalid, $isExist['id'], $symptoms_dose);
                }
            }
            $immune_medicine = filter_input(INPUT_POST, 'imedicine');
            $immune_dose     = filter_input(INPUT_POST, 'idose');
            if ($immune_medicine != "") {
                $isExist = $this->model->get_drug($immune_medicine, "immune", true);
                if (!empty($isExist)) {
                    $this->model->set_treatment($patientid, $hospitalid, $isExist['id'], $immune_dose);
                }
            }
            $health = filter_input(INPUT_POST, 'health');
            if ($health != 0) {
                $this->model->set_health($patientid, $hospitalid, $health);
            }
            $this->view->redirect('/doctor/units');
        } else {
            $this->view->redirect('/doctor/units');
        }
    }
    public function update_diagnosticAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        $patientid = filter_input(INPUT_POST, 'patientid');
        if (!empty($patientid)) {
            $illness   = filter_input(INPUT_POST, 'illness');
            $lab_data   = filter_input(INPUT_POST, 'lab_data');
            $age       = filter_input(INPUT_POST, 'agerisk');
            $sex       = filter_input(INPUT_POST, 'genrisk');
            $breath    = filter_input(INPUT_POST, 'breath');
            $temp      = filter_input(INPUT_POST, 'temp');
            $weakness  = filter_input(INPUT_POST, 'weakness');
            //--------INSERT DESEASES-------------------
            $this->model->set_risk($patientid, $illness, $age, $sex, $breath, $temp, $weakness);
            //--------COUNT RISK BASED ON WORLD STATS--------------
            $illness *= self::ILLNESSRISK;
            $age *= self::AGERISK;
            $temp = ((($temp - 36) * (100)) / (40 - 36));
            $temp *= self::TEMPRISK;
            $sex *= self::GENDERRISK;
            $breath *= self::BREATHRISK;
            $weakness *= self::WEAKNESSRISK;
            $riskPatient = $illness + $age + $temp + $sex + $breath + $weakness;
            $this->model->set_patient_risk($patientid, $hospitalid, $riskPatient);
            //------------INSERT LAB DATA--------------------
            $this->model->set_patient_lab_data($patientid, $lab_data);
            //$this->view->redirect('/doctor/patients');
        } else {
            $this->view->redirect('/doctor/patients');
        }
    }
    public function homeAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        $analytics  = $this->model->get_analytics($hospitalid);
        $vars       = [
            'analytics' => $analytics,
            'username'  => $username,
        ];
        $this->view->render($vars);
    }
    public function profileAction()
    {
        $this->isLogin();
        $username         = $this->model->get_doctor_username($this->id);
        $hospitalid       = $username['hospitalid'];
        $analytics        = $this->model->get_analytics($hospitalid);
        $my_info          = $this->model->get_hospital_info(null, $this->id);
        $total_number     = $this->model->patient_numbers($hospitalid, '');
        $recovered_number = $this->model->patient_numbers($hospitalid, 'recovered');
        $vars             = [
            'analytics'        => $analytics,
            'username'         => $username,
            'my_info'          => $my_info,
            'total_number'     => $total_number[0],
            'recovered_number' => $recovered_number[0],
        ];
        $this->view->render($vars);
    }
    public function edit_profileAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        $analytics  = $this->model->get_analytics($hospitalid);
        if (isset($_POST['fname'])) {
            $fname    = filter_input(INPUT_POST, 'fname');
            $lname    = filter_input(INPUT_POST, 'lname');
            $passport = filter_input(INPUT_POST, 'passport');
            $birthday = filter_input(INPUT_POST, 'birthday');
            $position = filter_input(INPUT_POST, 'position');
            $sex      = filter_input(INPUT_POST, 'sex');
            $this->model->doctor_update($this->id, $fname, $lname, $passport, $birthday, $sex, $position);
            $_POST = array();
        }
        $my_info = $this->model->get_hospital_info(null, $this->id);
        $vars    = [
            'analytics' => $analytics,
            'username'  => $username,
            'my_info'   => $my_info,
        ];
        $this->view->render($vars);
    }
    public function patientsAction()
    {
        $this->isLogin();
        $username     = $this->model->get_doctor_username($this->id);
        $hospitalid   = $username['hospitalid'];
        $analytics    = $this->model->get_analytics($hospitalid);
        $patient_type = filter_input(INPUT_GET, 'patients');
        if (empty($patient_type)) {
            $patients = $this->model->get_patients_by_status("waiting", $hospitalid);
        } elseif ($patient_type == "all") {
            $patients = $this->model->get_patients_by_hospital($hospitalid);
        } elseif ($patient_type == "hospitalized") {
            $patients = $this->model->get_hospitalized_patients($hospitalid);
        } else {
            $patients = $this->model->get_patients_by_status($patient_type, $hospitalid);
        }
        $vars = [
            'analytics' => $analytics,
            'username'  => $username,
            'patients'  => $patients,
        ];
        $this->view->render($vars);
    }
    public function pharmacyAction()
    {
        $this->isLogin();
        $username      = $this->model->get_doctor_username($this->id);
        $hospitalid    = $username['hospitalid'];
        $analytics     = $this->model->get_analytics($hospitalid);
        $medicine_type = filter_input(INPUT_GET, 'medicine');
        if (empty($medicine_type) || $medicine_type == "all") {
            $result = $this->model->get_drug();
            if (count($result) % 100 != 0) {
                $number_of_patients = intdiv(count($result), 100) + 1;
            } else {
                $number_of_patients = intdiv(count($result), 100);
            }
        } else {
            $result = $this->model->get_drug(null, $medicine_type);
        }
        $vars = [
            'analytics' => $analytics,
            'username'  => $username,
            'drugs'     => $result,
        ];
        $this->view->render($vars);
    }
    public function unitsAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        $analytics  = $this->model->get_analytics($hospitalid);
        $patients   = $this->model->get_tasks($hospitalid);
        $index      = 0;
        /*foreach ($checkedPatients as $row) {
        foreach ($patienthealth as $health) {
        if($row['patientid']==$health['id']){
        unset($patienthealth[$index]);
        $index++;
        break;
        }
        }
        }*/
        $vars = [
            'analytics' => $analytics,
            'username'  => $username,
            'patients'  => $patients,
        ];
        $this->view->render($vars);
    }
    public function view_patientAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        $analytics  = $this->model->get_analytics($hospitalid);
        $patientid  = filter_input(INPUT_GET, 'patientid');
        if (isset($patientid)) {
            $patient = $this->model->get_patient_info($patientid, $hospitalid);
            if ($patient != false) {
                $patient_risk        = $this->model->get_risk($patientid);
                $patient_dates       = $this->model->get_unit_info($patientid, $hospitalid);
                $patient_healths     = $this->model->get_patient_health($patient['id']);
                $plan_treatment_days = $this->model->get_plan_treatment_days($patient['id']);
                $plan_treatments     = $this->model->get_plan_treatments($patient['id'], $hospitalid);
                $my_treatments       = $this->model->get_treatments($patient['id'], $hospitalid);
                $my_treatment_days   = $this->model->treatment_days($patient['id'], $hospitalid);
                if ($patient_dates == null) {
                    $status = "в ожидании";
                } else {
                    $fromDate = $this->date_convert($patient_dates['fromDate']);
                    $toDate   = $this->date_convert($patient_dates['toDate']);
                    $status   = "госпитализирован с $fromDate по $toDate";
                }
                $vars = [
                    'analytics'           => $analytics,
                    'username'            => $username,
                    'hospitalid'          => $hospitalid,
                    'patientid'           => $patientid,
                    'patient'             => $patient,
                    'patient_healths'     => $patient_healths,
                    'status'              => $status,
                    'my_treatments'       => $my_treatments,
                    'plan_treatments'     => $plan_treatments,
                    'my_treatment_days'   => $my_treatment_days,
                    'plan_treatment_days' => $plan_treatment_days,
                    'risk'                => $patient_risk,
                    'illnessrisk'         => self::ILLNESSRISK,
                    'agerisk'             => self::AGERISK,
                    'genderrisk'          => self::GENDERRISK,
                    'breathrisk'          => self::BREATHRISK,
                    'temprisk'            => self::TEMPRISK,
                    'weaknessrisk'        => self::WEAKNESSRISK,
                ];
                $this->view->render($vars);
            } else {
                $this->view->redirect('/doctor/patients');
            }
        } else {
            $this->view->redirect('/hospital/patients');
        }
    }
    public function logoutAction()
    {
        $this->setId();
        $this->model->delete_key($this->id, self::SERVICE);
        session_destroy();
        $this->view->redirect('/');
    }
}
?>