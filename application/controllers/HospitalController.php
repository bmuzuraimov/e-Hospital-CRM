<?php
namespace application\controllers;

use application\core\Controller;

if (isset($_SESSION['user'])) {
    $GLOBALS['$ID'] = $_SESSION['user'];
} else {
    $GLOBALS['$ID'] = false;
}
class HospitalController extends Controller
{
    protected $id;
    const SERVICE      = 103;
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
    private function date_convert($date, $short = false)
    {
        if ($short === false) {
            $months = ["01" => "Января", "02" => "Февраля", "03" => "Марта", "04" => "Апреля", "05" => "Мая", "06" => "Июня", "07" => "Июля", "08" => "Августа", "09" => "Сентября", "10" => "Октября", "11" => "Ноября", "12" => "Декабря"];
            $year   = mb_substr($date, 0, 4);
            $month  = mb_substr($date, 5, 2);
            $month  = $months[$month];
            $day    = mb_substr($date, 8, 2);
            echo $day . " " . $month . ", " . $year;
        } else {
            $months = ["01" => "янв", "02" => "фев", "03" => "мар", "04" => "апр", "05" => "мая", "06" => "июн", "07" => "июл", "08" => "авг", "09" => "сен", "10" => "окт", "11" => "ноя", "12" => "дек"];
            $year   = mb_substr($date, 0, 4);
            $month  = mb_substr($date, 5, 2);
            $month  = $months[$month];
            $day    = mb_substr($date, 8, 2);
            $day    = (mb_substr($day, 0, 1) == 0) ? mb_substr($day, 1, 1) : $day;
            $time   = mb_substr($date, 10, 6);
            echo $time . ", " . $day . " " . $month;
        }
    }
    public function add_workerAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        $analytics  = $this->model->get_analytics($hospitalid);
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $phone = filter_input(INPUT_POST, 'phone');
            if (isset($phone)) {
                $isHead        = '0';
                $fname         = filter_input(INPUT_POST, 'fname');
                $lname         = filter_input(INPUT_POST, 'lname');
                $birthday      = filter_input(INPUT_POST, 'birthday');
                $sex           = filter_input(INPUT_POST, 'sex');
                $position      = filter_input(INPUT_POST, 'position');
                $temp_password = $this->model->get_random_passwd();
                $password      = password_hash($temp_password, PASSWORD_DEFAULT);
                $this->model->set_doctor($hospitalid, $isHead, $fname, $lname, $birthday, $sex, $phone, $position, $temp_password, $password);
                $this->view->redirect('/hospital/doctors');
            }
        }
        $vars = [
            'analytics' => $analytics,
            'username'  => $username,
        ];
        $this->view->render($vars);
    }
    public function chatAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        $analytics  = $this->model->get_analytics($hospitalid);
        $message    = filter_input(INPUT_POST, 'message');
        if (isset($message)) {
            $this->model->send_message($hospitalid, $this->id, self::SERVICE, $message);
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
        $userid     = $this->id;
        if ($_GET['chat'] == 'myhospital') {
            $chat = $this->model->get_myhospital_messages($hospitalid);
            foreach ($chat as $message):
                if ($message['userid'] == $userid) {
                    ?>
                                                                            <div class="chat-message">
                                                                                <div class="chat self">
                                                                                    <div class="user-photo"><img src="/public/images/doctor_profile/head.png" alt="head physician"></div>
                                                                                    <div class="message">
                                                                                        <p><?php echo $message['message']; ?></p>
                                                                                        <span class="time-left"><?=$this->date_convert($message['date'], true);?></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                                <?php
    } else {?>
                                                                            <div class="chat-message">
                                                                                <div class="chat doctor">
                                                                                    <div class="user-photo">
                                                                                        <img src="/public/images/doctor_profile/male.png" alt="male worker">
                                                                                    </div>
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
        } else {
            $chat = $this->model->get_hospitals_messages($hospitalid);
            foreach ($chat as $message):
                if ($message['userid'] == $userid) {
                    ?>
                                                                                <div class="chat self">
                                                                                    <div class="user-photo"><img src="/public/images/chath.jpg" alt="doctor"></div>
                                                                                    <div class="message">
                                                                                        <p><?php echo $message['message']; ?></p>
                                                                                        <span class="time-left"><?=$this->date_convert($message['date'], true);?></span>
                                                                                    </div>
                                                                                <?php
    } else {
                    ?>
                                                                                <div class="chat doctor">
                                                                                    <div class="user-photo"><img src="/public/images/chatd.jpg" alt="doctor"></div>
                                                                                    <div class="message">
                                                                                        <h6 class="sender"><?php echo $message['sender']; ?></h6>
                                                                                        <p><?php echo $message['message']; ?></p>
                                                                                        <span class="time-right"><?=$this->date_convert($message['date'], true);?></span>
                                                                                    </div>
                                                                                <?php
    }
            endforeach;
        }
    }
    public function doctorsAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        $analytics  = $this->model->get_analytics($hospitalid);
        $workers    = $this->model->get_workers($hospitalid);
        $vars       = [
            'analytics' => $analytics,
            'username'  => $username,
            'workers'   => $workers,
        ];
        $this->view->render($vars);
    }
    public function edit_patientAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        $analytics  = $this->model->get_analytics($hospitalid);
        $patientid  = filter_input(INPUT_GET, 'patientid');
        if (isset($patientid)) {
            $patient = $this->model->get_patient_info($patientid, $hospitalid);
            if ($patient != false) {
                $unit_info  = $this->model->get_unit_info($patientid, $hospitalid);
                $today      = date("Y-m-d");
                $isEditable = ($unit_info == false || $today < $unit_info['toDate']) ? true : false;
                $vars       = [
                    'analytics'  => $analytics,
                    'patient'    => $patient,
                    'username'   => $username,
                    'hospitalid' => $hospitalid,
                    'isEditable' => $isEditable,
                ];
                $this->view->render($vars);
            } else {
                $this->view->redirect('/hospital/patients');
            }
        } else {
            $this->view->redirect('/hospital/patients');
        }
    }
    public function update_patientAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $id = filter_input(INPUT_POST, 'id');
            if (isset($id)) {
                $fname     = filter_input(INPUT_POST, 'fname');
                $lname     = filter_input(INPUT_POST, 'lname');
                $bday      = filter_input(INPUT_POST, 'bday');
                $sex       = filter_input(INPUT_POST, 'sex');
                $emcontact = filter_input(INPUT_POST, 'phone');
                $symptoms  = filter_input(INPUT_POST, 'symptoms');
                $this->model->update_patient_info($id, $hospitalid, $fname, $lname, $bday, $sex, $emcontact, $symptoms);
                $_POST = array();
            }
        }
    }
    public function edit_profileAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        $analytics  = $this->model->get_analytics($hospitalid);
        $name       = filter_input(INPUT_POST, 'name');
        if (isset($name)) {
            $address  = filter_input(INPUT_POST, 'address');
            $city     = filter_input(INPUT_POST, 'city');
            $oblast   = filter_input(INPUT_POST, 'oblast');
            $tunits   = filter_input(INPUT_POST, 'tunits');
            $position = filter_input(INPUT_POST, 'position');
            $fname    = filter_input(INPUT_POST, 'fname');
            $lname    = filter_input(INPUT_POST, 'lname');
            $birthday = filter_input(INPUT_POST, 'birthday');
            $sex      = filter_input(INPUT_POST, 'sex');
            $passport = filter_input(INPUT_POST, 'passport');
            $this->model->hospital_update($hospitalid, $name, $address, $city, $oblast, $position, $tunits);
            $this->model->doctor_update($this->id, $fname, $lname, $birthday, $sex, $passport);
            $_POST = array();
        }
        $doctor_info = $this->model->get_hospital_info(null, $this->id);
        $vars        = [
            'username'    => $username,
            'analytics'   => $analytics,
            'doctor_info' => $doctor_info,
        ];
        $this->view->render($vars);
    }
    public function fill_pharamacyAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        $analytics  = $this->model->get_analytics($hospitalid);
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $name = filter_input(INPUT_POST, 'name');
            if (isset($name)) {
                $purpose     = filter_input(INPUT_POST, 'purpose');
                $maxdose     = filter_input(INPUT_POST, 'maxdose');
                $description = filter_input(INPUT_POST, 'description');
                $isExists    = $this->model->is_medicine_exists($name);
                if (empty($isExists)) {
                    $this->model->set_drug($name, $purpose, $maxdose, $description);
                }
            }
        }
        $vars = [
            'analytics' => $analytics,
            'username'  => $username,
        ];
        $this->view->render($vars);
    }
    public function homeAction()
    {
        $this->isLogin();
        $username        = $this->model->get_doctor_username($this->id);
        $hospitalid      = $username['hospitalid'];
        $analytics       = $this->model->get_analytics($hospitalid);
        $patients_growth = $this->model->get_patient_growth($hospitalid);
        $vars            = [
            'analytics'       => $analytics,
            'username'        => $username,
            'patients_growth' => $patients_growth,
        ];
        $this->view->render($vars);
    }
    public function my_profileAction()
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
            'my_info'          => $my_info,
            'total_number'     => $total_number[0],
            'recovered_number' => $recovered_number[0],
            'username'         => $username,
        ];
        $this->view->render($vars);
    }
    public function patientsAction()
    {
        $this->isLogin();
        $username          = $this->model->get_doctor_username($this->id);
        $hospitalid        = $username['hospitalid'];
        $analytics         = $this->model->get_analytics($hospitalid);
        $waiting_list      = $this->model->get_diagnosed_patients($hospitalid);
        $hospitalized_list = $this->model->get_hospitalized_patients($hospitalid);
        $recovered_list    = $this->model->get_recovered($hospitalid);
        $que_list          = $this->model->get_que_patients($hospitalid);
        $vars              = [
            'analytics'         => $analytics,
            'username'          => $username,
            'hospitalid'        => $hospitalid,
            'waiting_list'      => $waiting_list,
            'hospitalized_list' => $hospitalized_list,
            'que_list'          => $que_list,
            'recovered_list'    => $recovered_list,
        ];
        $this->view->render($vars);
    }
    public function pharmacyAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        $analytics  = $this->model->get_analytics($hospitalid);
        $medicine   = filter_input(INPUT_GET, 'medicine');
        if (empty($medicine) || $medicine == "all") {
            $result = $this->model->get_drug();
        } else {
            $result = $this->model->get_drug(null, $medicine);
        }
        $vars = [
            'analytics' => $analytics,
            'username'  => $username,
            'drugs'     => $result,
        ];
        $this->view->render($vars);
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
    public function unitsAction()
    {
        $this->isLogin();
        $username           = $this->model->get_doctor_username($this->id);
        $hospitalid         = $username['hospitalid'];
        $analytics          = $this->model->get_analytics($hospitalid);
        $occupied_units     = $this->model->get_occupied_units($hospitalid);
        $diagnosed_patients = $this->model->get_diagnosed_patients($hospitalid);
        $empty_units        = $this->model->get_empty_units($hospitalid, $analytics['tunits']);
        $vars               = [
            'analytics'          => $analytics,
            'username'           => $username,
            'hospitalid'         => $hospitalid,
            'occupied_units'     => $occupied_units,
            'diagnosed_patients' => $diagnosed_patients,
            'empty_units'        => $empty_units,
        ];
        $this->view->render($vars);
    }
    public function delete_unitAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        $patientid  = filter_input(INPUT_GET, 'patientid');
        $link       = filter_input(INPUT_GET, 'link');
        $this->model->delete_unit($patientid);
        $this->model->unset_hospitalized_patient($patientid, $hospitalid);
        $this->view->redirect($link);
    }
    public function confirm_patientAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        $analytics  = $this->model->get_analytics($hospitalid);
        $patientid  = filter_input(INPUT_POST, 'patientid');
        $day        = filter_input(INPUT_POST, 'days');
        $type       = filter_input(INPUT_POST, 'type');
        if ($type == "confirm") {
            $fromDate = date("Y-m-d");
            $toDate   = strtotime("+$day days 13:00");
            $toDate   = date('Y-m-d H:i', $toDate);
            if ($analytics['aunits'] > 0 && $day != 0) {
                $unit = $this->model->get_available_unit($hospitalid, $analytics['tunits']);
                $this->model->set_patient_hospitalized($patientid, $hospitalid, $unit, $fromDate, $toDate);
                $this->view->redirect('/hospital/patients');
            } else {
                $this->view->redirect('/hospital/patients');
            }
        } else if ($type == "unit") {
            $unit     = filter_input(INPUT_POST, 'unit');
            $fromDate = date("Y-m-d");
            $toDate   = strtotime("+$day days 13:00");
            $toDate   = date('Y-m-d H:i', $toDate);
            if ($analytics['aunits'] > 0 && $day != 0) {
                $this->model->set_patient_hospitalized($patientid, $hospitalid, $unit, $fromDate, $toDate);
                $this->view->redirect('/hospital/units');
            } else {
                $this->view->redirect('/hospital/units');
            }
        } else {
            $result   = $this->model->get_closest_date_unit($hospitalid);
            $fromDate = $result['toDate'];
            $unit     = $result['unit'];
            $toDate   = strtotime($fromDate);
            $toDate   = strtotime("+$day days 13:00", $toDate);
            $toDate   = date('Y-m-d H:i', $toDate);
            $this->model->set_patient_hospitalized($patientid, $hospitalid, $unit, $fromDate, $toDate);
            $this->view->redirect('/hospital/patients');
        }
    }
    public function view_doctorAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        $doctorid   = filter_input(INPUT_GET, 'doctorid');
        if (isset($doctorid)) {
            $doctor_info = $this->model->get_hospital_info(null, $doctorid);
            $analytics   = $this->model->get_analytics($hospitalid);
            if ($doctor_info != false) {
                $isMyDoctor = ($hospitalid == $doctor_info['hospitalid']) ? true : false;
                $vars       = [
                    'analytics'   => $analytics,
                    'username'    => $username,
                    'doctor_info' => $doctor_info,
                    'isMyDoctor'  => $isMyDoctor,
                ];
                $this->view->render($vars);
            } else {
                $this->view->redirect('/hospital/doctors');
            }
        } else {
            $this->view->redirect('/hospital/doctors');
        }
    }
    private function find_closest_age($date1, $date2)
    {
        $result = date_diff(date_create($date1), date_create($date2));
        $result = $result->format('%y');
        $result = (($result) / (90)) * 10;
        return $result;
    }
    private function find_closest_sex($sex1, $sex2)
    {
        if ($sex1 == $sex2) {
            $result = 0;
        } else {
            $result = 10;
        }
        return $result;
    }
    private function find_closest_blood_type($type1, $type2)
    {
        $types = ["I" => 1, "II" => 2, "III" => 3, "IV" => 4];
        if (array_key_exists($type1, $types) && array_key_exists($type2, $types)) {
            $type1  = $types[$type1];
            $type2  = $types[$type2];
            $result = abs($type2 - $type1);
            $result = (($result) / (3)) * (10);
            return $result;
        } else {
            return 0;
        }
    }
    private function find_closest_symptoms($symptom1, $symptom2)
    {
        if ($symptom1 == "" || $symptom2 == "") {
            return 10;
        } else {
            $symptom1 = explode(",", $symptom1);
            $symptom2 = explode(",", $symptom2);
            $symptom1 = array_filter(array_map('trim', $symptom1));
            $symptom2 = array_filter(array_map('trim', $symptom2));
            $result   = array_diff($symptom1, $symptom2);
            $result   = count($result);
            return (count($symptom1) == 0) ? 0 : $result / count($symptom1) * 10;
        }
    }
    private function get_closest_illness($illness1, $illness2, $breath1, $breath2, $temperature1, $temperature2)
    {
        $illness     = abs($illness2 - $illness1);
        $breath      = abs($breath2 - $breath1);
        $temperature = abs($temperature2 - $temperature1);
        $illness     = $illness / 100 * 10;
        $breath      = $breath / 80 * 10;
        $temperature = $temperature / 4 * 10;
        return $illness + $breath + $temperature;
    }
    private function get_closest_patient($current_patient, $current_patient_risk)
    {
        $all_patients = $this->model->get_matching_patient($current_patient['id']);
        $min_points1  = INF;
        $min_points2  = 0;
        for ($i = 0; $i < count($all_patients); $i++) {
            $closest_age = $this->find_closest_age($current_patient['age'], $all_patients[$i]['age']);
            $min_points2 += $closest_age;
            $closest_sex = $this->find_closest_sex($current_patient['sex'], $all_patients[$i]['sex']);
            $min_points2 += $closest_sex;
            $closest_blood_type = $this->find_closest_blood_type($current_patient['blood_type'], $all_patients[$i]['blood_type']);
            $min_points2 += $closest_blood_type;
            $closest_symptoms = $this->find_closest_symptoms($current_patient['symptoms'], $all_patients[$i]['symptoms']);
            $min_points2 += $closest_symptoms;
            $closest_illness = $this->get_closest_illness($current_patient_risk['illness'], $all_patients[$i]['illness'], $current_patient_risk['breath'], $all_patients[$i]['breath'], $current_patient_risk['temperature'], $all_patients[$i]['temperature']);
            $min_points2 += $closest_illness;
            if ($min_points2 < $min_points1) {
                $matching_patient = $all_patients[$i]['id'];
                $min_points1      = $min_points2;
            }
            $min_points2 = 0;
        }
        return $matching_patient;
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
                $patient_risk             = $this->model->get_risk($patientid);
                $patient_dates            = $this->model->get_unit_info($patientid, $hospitalid);
                $patient_healths          = $this->model->get_patient_health($patient['id']);
                $plan_treatment_days      = $this->model->get_plan_treatment_days($patient['id']);
                $plan_treatments          = $this->model->get_plan_treatments($patient['id'], $hospitalid);
                $my_treatments            = $this->model->get_treatments($patient['id'], $hospitalid);
                $my_treatment_days        = $this->model->treatment_days($patient['id'], $hospitalid);
                $matching_patient         = $this->get_closest_patient($patient, $patient_risk);
                $matching_patient_risk    = $this->model->get_risk($matching_patient);
                $ai_treatments            = $this->model->get_treatments($matching_patient, $hospitalid);
                $ai_treatment_days        = $this->model->treatment_days($matching_patient, $hospitalid);
                $status                   = ($patient_dates == null) ? "в ожидании" : "госпитализирован с " . $patient_dates['fromDate'] . " по " . $patient_dates['toDate'];
                $matching_patient_healths = $this->model->get_patient_health($matching_patient);
                $matching_patient         = $this->model->get_patient_info($matching_patient, $hospitalid);
                $vars                     = [
                    'analytics'                => $analytics,
                    'username'                 => $username,
                    'hospitalid'               => $hospitalid,
                    'patientid'                => $patientid,
                    'patient'                  => $patient,
                    'patient_healths'          => $patient_healths,
                    'status'                   => $status,
                    'matching_patient'         => $matching_patient,
                    'matching_patient_healths' => $matching_patient_healths,
                    'matching_patient_risk'    => $matching_patient_risk,
                    'my_treatments'            => $my_treatments,
                    'ai_treatments'            => $ai_treatments,
                    'plan_treatments'          => $plan_treatments,
                    'my_treatment_days'        => $my_treatment_days,
                    'ai_treatment_days'        => $ai_treatment_days,
                    'plan_treatment_days'      => $plan_treatment_days,
                    'risk'                     => $patient_risk,
                    'illnessrisk'              => self::ILLNESSRISK,
                    'agerisk'                  => self::AGERISK,
                    'genderrisk'               => self::GENDERRISK,
                    'breathrisk'               => self::BREATHRISK,
                    'temprisk'                 => self::TEMPRISK,
                    'weaknessrisk'             => self::WEAKNESSRISK,
                ];
                $this->view->render($vars);
            } else {
                $this->view->redirect('/hospital/patients');
            }
        } else {
            $this->view->redirect('/hospital/patients');
        }
    }
    public function save_treatmentAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $patientid = filter_input(INPUT_POST, 'patientid');
            if (isset($patientid)) {
                $this->model->clear_treatment_recomendation($patientid);
                unset($_POST['patientid']);
                $counter        = 0;
                $medicine       = '';
                $dose           = '';
                $time           = '';
                $day            = '';
                $medicine_index = 0;
                $dose_index     = 1;
                $time_index     = 2;
                $day_index      = 3;
                foreach ($_POST as $varname => $value) {
                    echo $value . "<br>";
                    switch ($counter) {
                        case $medicine_index:
                            $medicine = $value;
                            break;
                        case $dose_index:
                            $dose = $value;
                            break;
                        case $time_index:
                            $time = $value;
                            break;
                        case $day_index:
                            $day = $value;
                            break;
                    }
                    if ($counter % $day_index == 0 && $counter != 0) {
                        $medicine = mb_strtolower($medicine, 'UTF-8');
                        $medicine = mb_convert_case($medicine, MB_CASE_TITLE, "UTF-8");
                        $drugid   = $this->model->is_medicine_exists($medicine);
                        $medicine_index += 4;
                        $dose_index += 4;
                        $time_index += 4;
                        $day_index += 4;
                        if ($drugid != false) {
                            switch ($time) {
                                case 'morning':
                                    $time = 8;
                                    break;
                                case 'afternoon':
                                    $time = 13;
                                    break;
                                case 'evening':
                                    $time = 18;
                                    break;
                                default:
                                    $time = 13;
                                    break;
                            }
                            $this->model->add_suggestion($patientid, $hospitalid, $drugid, $dose, $day, $time, $this->id);
                        } else {
                            echo "<h3 class=\"page-title msg success\">Лекарство не найдено!</h3>";
                        }
                    }
                    $counter++;
                }
                echo "<h3 class=\"page-title msg success\">Сохранено!</h3>";
                $_POST = array();
            } else {
                echo "<h3 class=\"page-title msg error\">Произошла ошибка!</h3>";
                $_POST = array();
            }
        }
    }
    public function set_patient_statusAction()
    {
        $this->isLogin();
        $username   = $this->model->get_doctor_username($this->id);
        $hospitalid = $username['hospitalid'];
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $patientid = filter_input(INPUT_POST, 'patientid');
            if (isset($patientid)) {
                $link = filter_input(INPUT_POST, 'link');
                $this->model->set_patient_recovered($patientid, $hospitalid);
                $this->view->redirect($link);
            } else {
                $this->view->redirect('/hospital/patients?show=recovered');
            }
        } else {
            $this->view->redirect('/hospital/patients?show=recovered');
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