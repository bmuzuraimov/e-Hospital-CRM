<?php
namespace application\models;

use application\core\Model;

class Hospital extends Model
{
    public function get_workers($hospitalid)
    {
        $params = [
            'hospitalid' => $hospitalid,
        ];
        $result = $this->db->rows('SELECT * FROM doctor_users WHERE hospitalid = :hospitalid AND isHead = 0', $params);
        return $result;
    }
    public function clear_treatment_recomendation($patientid)
    {
        $params = [
            'patientid' => $patientid,
        ];
        $result = $this->db->query('DELETE FROM treatment_recomendation WHERE patientid = :patientid', $params);
        return $result;
    }
    public function patient_numbers($hospital, $status = '')
    {
        $params = [
            'hospitalid' => $hospital,
        ];
        if ($status != '') {
            $params['status'] = $status;
            $result           = $this->db->row('SELECT COUNT(*) FROM patients_database WHERE hospitalid = :hospitalid AND status = :status', $params);
        } else {
            $result = $this->db->row('SELECT COUNT(*) FROM patients_database WHERE hospitalid = :hospitalid', $params);
        }
        return $result;
    }
    public function get_hospitals_messages($hospitalid)
    {
        $params = [
            'hospitalid' => $hospitalid,
        ];
        $result = $this->db->rows('SELECT * FROM (SELECT hospitals_chat.userid, hospitals_chat.message, CONCAT(doctor_users.fname, " ", doctor_users.lname) as sender, date FROM hospitals_chat LEFT JOIN doctor_users ON hospitals_chat.userid = doctor_users.id ORDER BY hospitals_chat.date DESC LIMIT 20) sub ORDER BY date ASC', $params);
        return $result;
    }
    public function get_patient_growth($hospitalid)
    {
        $params = [
            'hospitalid' => $hospitalid,
        ];
        $result = $this->db->rows('SELECT * FROM ( SELECT patients_database.id, DATE_FORMAT(DATE( patients_database.sickDate ), "%d-%m-%Y") as day, COUNT( patients_database.id ) AS patient FROM patients_database WHERE patients_database.hospitalid = :hospitalid GROUP BY DATE( patients_database.sickDate ) ORDER BY day DESC LIMIT 7 ) sub ORDER BY day ASC', $params);
        return $result;
    }
    public static function get_health($patientid, $hospitalid)
    {
        $doctor = new Hospital();
        $params = [
            'patientid'  => $patientid,
            'hospitalid' => $hospitalid,
        ];
        $result = $doctor->db->rows('SELECT * FROM (SELECT patientid, hospitalid, improvement, data FROM patients_condition WHERE patientid = :patientid AND hospitalid = :hospitalid ORDER BY data DESC LIMIT 6) sub ORDER BY data ASC', $params);
        return $result;
    }
    public function add_suggestion($patientid, $hospitalid, $drugid, $dose, $day, $time, $signature){
        $params = [
            'patientid'=>$patientid,
            'hospitalid'=>$hospitalid,
            'drugid'=>$drugid,
            'dose'=>$dose,
            'day'=>$day,
            'time'=>$time,
            'signature'=>$signature,
        ];
        $this->db->query('INSERT INTO treatment_recomendation (patientid, hospitalid, drugid, dose, day, time, signature) VALUES(:patientid, :hospitalid, :drugid, :dose, :day, :time, :signature)', $params);
    }
    public static function last_patient_of_unit($unit, $hospitalid)
    {
        $doctor = new Hospital();
        $today  = date("Y-m-d");
        $params = [
            'unit'  => $unit,
            'today' => $today,
            'hospitalid' => $hospitalid,
        ];
        $result = $doctor->db->row('SELECT patients_database.id, patients_database.fname, patients_database.lname FROM patients_database INNER JOIN hospital_units ON patients_database.id = hospital_units.patientid WHERE hospital_units.hospitalid = :hospitalid AND hospital_units.unit = :unit AND hospital_units.toDate < :today ORDER BY toDate DESC LIMIT 1', $params);
        return $result;
    }
    public function get_recovered($hospitalid)
    {
        $today  = date("Y-m-d");
        $params = [
            'hospitalid' => $hospitalid,
            'today'      => $today,
        ];
        $result = $this->db->rows('SELECT patients_database.id, patients_database.fname, patients_database.lname, patients_database.age, patients_database.status FROM patients_database INNER JOIN hospital_units ON patients_database.id = hospital_units.patientid WHERE patients_database.hospitalid = :hospitalid AND hospital_units.toDate < :today', $params);
        return $result;
    }
    public static function get_patient_image($age, $gender, $improve = true)
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
    public function set_doctor($hospitalid, $isHead, $fname, $lname, $birthday, $sex, $phone, $position, $temp_password, $password)
    {
        $params = [
            'hospitalid'    => $hospitalid,
            'isHead'        => $isHead,
            'fname'         => $fname,
            'lname'         => $lname,
            'birthday'      => $birthday,
            'sex'           => $sex,
            'phone'         => $phone,
            'position'      => $position,
            'temp_password' => $temp_password,
            'password'      => $password,
        ];
        $result = $this->db->query('INSERT INTO doctor_users(hospitalid, isHead, fname, lname, birthday, sex, phone, position,temp_password, password) VALUES (:hospitalid,:isHead,:fname,:lname,:birthday,:sex,:phone,:position, :temp_password, :password)', $params);
        return $result;
    }
    public function hospital_update($id, $name, $address, $city, $oblast, $position, $tunits)
    {
        $params = [
            'id'       => $id,
            'name'     => $name,
            'address'  => $address,
            'city'     => $city,
            'oblast'   => $oblast,
            'position' => $position,
            'tunits'   => $tunits,
        ];
        $result = $this->db->query('UPDATE hospitals_database SET name = :name, address = :address, city = :city, oblast = :oblast, position = :position,  tunits = :tunits WHERE  id =:id', $params);
        return $result;
    }
    public function doctor_update($id, $fname, $lname, $birthday, $sex, $passport)
    {
        $params = [
            'id'       => $id,
            'fname'    => $fname,
            'lname'    => $lname,
            'birthday' => $birthday,
            'sex'      => $sex,
            'passport'      => $passport,
        ];
        $result = $this->db->query('UPDATE doctor_users SET fname=:fname, lname=:lname, birthday=:birthday, sex=:sex, passport=:passport WHERE id = :id', $params);
        return $result;
    }
    public function update_patient_info($id, $hospitalid, $first_name, $surname, $age, $sex, $emcontact, $symptoms)
    {
        $params = [
            'firstname'  => $first_name,
            'lname'      => $surname,
            'age'        => $age,
            'sex'        => $sex,
            'emcontact'  => $emcontact,
            'symptoms'   => $symptoms,
            'id'         => $id,
            'hospitalid' => $hospitalid,
        ];
        $result = $this->db->query('UPDATE patients_database SET fname = :firstname, lname = :lname, age = :age, sex = :sex, emcontact = :emcontact, symptoms = :symptoms WHERE id= :id AND hospitalid = :hospitalid', $params);
        return $result;
    }
    public function unset_hospitalized_patient($id, $hospitalid)
    {
        $params = [
            'patientid'  => $id,
            'hospitalid' => $hospitalid,
            'status'     => 'waiting',
        ];
        $result = $this->db->query('UPDATE patients_database SET status = :status WHERE id = :patientid AND hospitalid=:hospitalid', $params);
        return $result;
    }
    public function set_patient_hospitalized($patientid, $hospitalid, $unit, $fromDate, $toDate)
    {
        $params = [
            'patientid'  => $patientid,
            'unit'       => $unit,
            'hospitalid' => $hospitalid,
            'fromDate'   => $fromDate,
            'toDate'     => $toDate,
        ];
        $result = $this->db->query('INSERT INTO hospital_units (patientid, unit, hospitalid, fromDate, toDate) VALUES (:patientid, :unit, :hospitalid, :fromDate, :toDate)', $params);
        //set status hospitalized
        $params = [
            'patientid'  => $patientid,
            'hospitalid' => $hospitalid,
            'status'     => 'hospitalized',
        ];
        $result = $this->db->query('UPDATE patients_database SET status = :status WHERE id = :patientid
    AND hospitalid=:hospitalid', $params);
        return $result;
    }
    public function set_patient_recovered($id, $hospitalid)
    {
        $today  = date("Y-m-d");
        $params = [
            'recDate'    => $today,
            'id'         => $id,
            'hospitalid' => $hospitalid,
        ];
        $result = $this->db->query('UPDATE patients_database SET status = recovered, recover = 1, recDate = :recDate WHERE id = :id AND hospitalid = :hospitalid', $params);
        return $result;
    }
    public function delete_unit($patientid)
    {
        $today  = date("Y-m-d H:i");
        $params = [
            'patientid' => $patientid,
        ];
        $result = $this->db->query('DELETE FROM hospital_units WHERE patientid = :patientid', $params);
        return $result;
    }
    public function get_empty_units($hospitalid, $total)
    {
        $empty  = array();
        $today  = date("Y-m-d");
        $time   = date('H:i', time());
        $params = [
            'hospitalid' => $hospitalid,
            'today'      => $today,
            'unit'       => '',
        ];
        for ($i = 1; $i < ($total + 1); $i++) {
            $params['unit'] = $i;
            $result         = $this->db->row('SELECT unit FROM hospital_units WHERE hospitalid = :hospitalid AND unit = :unit AND :today BETWEEN fromDate AND toDate LIMIT 1', $params);
            if ($result == false) {
                $empty[] = $i;
            } else {
                continue;
            }
        }
        return $empty;
    }
    public function get_closest_date_unit($hospitalid)
    {
        $today  = date("Y-m-d H:i");
        $params = [
            'hospitalid' => $hospitalid,
            'today'      => $today,
        ];
        $result = $this->db->row('SELECT unit, max(toDate) as toDate FROM hospital_units WHERE hospitalid = :hospitalid AND :today< toDate GROUP BY unit ORDER BY toDate ASC LIMIT 1', $params);
        return $result;
    }
    public static function get_ques_by_unit($hospitalid, $unit)
    {
        $doctor = new Hospital();
        $today  = date("Y-m-d H:i");
        $params = [
            'hospitalid' => $hospitalid,
            'unit'       => $unit,
            'today'      => $today,
        ];
        $result = $doctor->db->rows('SELECT patients_database.id, patients_database.lname, patients_database.fname FROM hospital_units INNER JOIN patients_database ON hospital_units.patientid = patients_database.id WHERE hospital_units.hospitalid = :hospitalid AND hospital_units.unit=:unit AND :today<hospital_units.fromDate', $params);
        return $result;
    }
    public function get_que_patients($hospitalid)
    {
        $today  = date("Y-m-d H:i");
        $params = [
            'hospitalid' => $hospitalid,
            'today'      => $today,
        ];
        $result = $this->db->rows('SELECT hospital_units.patientid, patients_database.lname, patients_database.fname, patients_database.age, patients_database.risk FROM hospital_units LEFT JOIN patients_database ON hospital_units.patientid = patients_database.id WHERE hospital_units.hospitalid = :hospitalid AND :today<hospital_units.fromDate', $params);
        return $result;
    }
    public function get_available_unit($hospitalid, $total)
    {
        $available_unit = null;
        $today          = date("Y-m-d H:i");
        $params         = [
            'hospitalid' => $hospitalid,
            'today'      => $today,
            'unit'       => '',
        ];
        for ($i = 1; $i < ($total + 1); $i++) {
            $params['unit'] = $i;
            $result         = $this->db->row('SELECT unit FROM hospital_units WHERE hospitalid = :hospitalid AND unit = :unit AND :today BETWEEN fromDate AND toDate', $params);
            if ($result == true) {
                continue;
            } else {
                $available_unit = $i;
                break;
            }
        }
        return $available_unit;
    }
    public function get_occupied_units($hospitalid)
    {
        $today  = date("Y-m-d");
        $time   = date('H:i', time());
        $params = [
            'hospitalid' => $hospitalid,
            'today'      => $today,
        ];
        $result = $this->db->rows('SELECT patients_database.*, hospital_units.patientid, hospital_units.unit, hospital_units.fromDate, hospital_units.toDate, hospital_units.time FROM hospital_units INNER JOIN patients_database ON hospital_units.patientid = patients_database.id WHERE hospital_units.hospitalid = :hospitalid AND :today BETWEEN hospital_units.fromDate AND hospital_units.toDate ORDER BY hospital_units.unit ASC', $params);
        return $result;
    }
}
