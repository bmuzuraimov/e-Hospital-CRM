<?php
namespace application\models;

use application\core\Model;

class Doctor extends Model
{
    public function add_patient($hospitalid, $first_name, $last_name, $age, $sex, $passport, $phone, $symptoms)
    {
        $params = [
            'hospitalid' => $hospitalid,
            'fname'      => $first_name,
            'lname'      => $last_name,
            'age'        => $age,
            'sex'        => $sex,
            'passport'   => $passport,
            'emcontact'  => $phone,
            'symptoms'   => $symptoms,
        ];
        $result    = $this->db->query('INSERT INTO patients_database (hospitalid, fname, lname, age, sex, passport, emcontact, symptoms) VALUES (:hospitalid, :fname, :lname, :age, :sex, :passport, :emcontact, :symptoms)', $params);
        $patientid = $this->db->row('SELECT id FROM patients_database ORDER BY id DESC LIMIT 1;');
        return $patientid[0];
    }

    public function find_matching_patient()
    {
        $params = [

        ];
        $result = $this->db->rows('SELECT patients_database.id, patients_database.age, patients_database.sex, patients_database.blood_type, patients_database.symptoms, illness_data.illness, illness_data.breath, illness_data.temperature, illness_data.weakness FROM patients_database INNER JOIN illness_data ON patients_database.id = illness_data.patient');
        return $result;
    }

    public function get_tasks($hospitalid)
    {
        $today  = date("Y-m-d");
        $params = [
            'hospitalid' => $hospitalid,
            'today'      => $today,
        ];
        $result = $this->db->rows('SELECT patients_database.id, hospital_units.unit, patients_database.fname, patients_database.lname, patients_database.age, patients_database.sex, patients_database.risk, hospital_units.fromDate, hospital_units.toDate FROM patients_database INNER JOIN hospital_units ON patients_database.id = hospital_units.patientid WHERE hospital_units.hospitalid = :hospitalid AND :today BETWEEN hospital_units.fromDate AND hospital_units.toDate ORDER BY hospital_units.unit ASC', $params);
        return $result;
    }

    public function get_checked_patient($patientid, $hospitalid)
    {
        $today  = date("Y-m-d");
        $params = [
            'patientid'  => $patientid,
            'hospitalid' => $hospitalid,
            'today'      => $today,
        ];
        $result = $this->db->row('SELECT * FROM patients_condition WHERE patientid = :patientid AND hospitalid = :hospitalid AND data = :today', $params);
        return $result;
    }
    public function doctor_update($id, $fname, $lname, $passport, $birthday, $sex, $position)
    {
        $params = [
            'id'       => $id,
            'fname'    => $fname,
            'lname'    => $lname,
            'passport' => $passport,
            'birthday' => $birthday,
            'sex'      => $sex,
            'position' => $position,
        ];
        $result = $this->db->query('UPDATE doctor_users SET fname=:fname, lname=:lname, passport=:passport, birthday=:birthday, sex=:sex, position = :position WHERE id = :id', $params);
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

    public function get_sex($sex)
    {
        switch ($sex) {
            case 'male':
                return "<input for=\"sex_1\" type=\"radio\" class=\"disabled-radio\" id=\"sex_1\" disabled checked/><label class=\"text-input\">Мужской</label>
                <input type=\"hidden\" name=\"genrisk\" value=\"100\" />";
                break;
            case 'female':
                return "<input for=\"sex_2\" type=\"radio\" class=\"disabled-radio\" id=\"sex_2\" disabled checked/><label class=\"text-input\">Женский</label>
                <input type=\"hidden\" name=\"genrisk\" value=\"60\" />";
                break;
        }
    }
    public function get_age_range($age)
    {
        switch ($age) {
            case ($age >= 0 && $age <= 9):
                return "<input type=\"radio\" id=\"age_1\" class=\"disabled-radio\" disabled checked/><label class=\"text-input\" for=\"age_1\">0-9</label>
                <input type=\"hidden\" name=\"agerisk\" value=\"0\" />";
                break;
            case ($age >= 10 && $age <= 39):
                return "<input type=\"radio\" id=\"age_2\" class=\"disabled-radio\" disabled checked/><label class=\"text-input\" for=\"age_2\">10-39</label>
                <input type=\"hidden\" name=\"agerisk\" value=\"1.3\" />";
                break;
            case ($age >= 40 && $age <= 49):
                return "<input type=\"radio\" id=\"age_3\" class=\"disabled-radio\" disabled checked/><label class=\"text-input\" for=\"age_3\">40-49</label>
                <input type=\"hidden\" name=\"agerisk\" value=\"2.8\" />";
                break;
            case ($age >= 50 && $age <= 59):
                return "<input type=\"radio\" id=\"age_4\" class=\"disabled-radio\" disabled checked/><label class=\"text-input\" for=\"age_4\">50-59</label>
                <input type=\"hidden\" name=\"agerisk\" value=\"9\" />";
                break;
            case ($age >= 60 && $age <= 69):
                return "<input type=\"radio\" id=\"age_5\" class=\"disabled-radio\" disabled checked/><label class=\"text-input\" for=\"age_5\">60-69</label>
                <input type=\"hidden\" name=\"agerisk\" value=\"24.3\" />";
                break;
            case ($age >= 70 && $age <= 79):
                return "<input type=\"radio\" id=\"age_6\" class=\"disabled-radio\" disabled checked/><label class=\"text-input\" for=\"age_6\">70-79</label>
                <input type=\"hidden\" name=\"agerisk\" value=\"53.8\" />";
                break;
            case ($age >= 80):
                return "<input type=\"radio\" id=\"age_7\" class=\"disabled-radio\" disabled checked/><label class=\"text-input\" for=\"age_7\">>80</label>
                <input type=\"hidden\" name=\"agerisk\" value=\"100\" />";
                break;
        }
    }
    public static function risk_exists($patientid)
    {
        $doctor = new Doctor();
        $params = [
            'patientid' => $patientid,
        ];
        $result = $doctor->db->row('SELECT EXISTS(SELECT * FROM illness_data WHERE patient = :patientid)', $params);
        return $result[0];
    }
    public function set_risk($patientid, $illness, $age, $sex, $breath, $temp, $weak)
    {
        $params = [
            'patient' => $patientid,
            'illness' => $illness,
            'age'     => $age,
            'sex'     => $sex,
            'breath'  => $breath,
            'temp'    => $temp,
            'weak'    => $weak,
        ];
        $result = $this->db->query('INSERT INTO illness_data (patient, illness, age, sex, breath, temperature, weakness) VALUES (:patient, :illness, :age, :sex, :breath, :temp, :weak)', $params);
        return $result;
    }
    public function set_health($patientid, $hospitalid, $improvement)
    {
        $params = [
            'patientid'   => $patientid,
            'hospitalid'  => $hospitalid,
            'improvement' => $improvement,
        ];
        $result = $this->db->query('INSERT INTO patients_condition (patientid, hospitalid, improvement) VALUES (:patientid, :hospitalid, :improvement)', $params);
        return $result;
    }
    public function set_treatment($patientid, $hospitalid, $drugid, $dose = null)
    {
        $params = [
            'patientid'  => $patientid,
            'hospitalid' => $hospitalid,
            'drugid'     => $drugid,
            'dose'       => $dose,
        ];
        $result = $this->db->query('INSERT INTO treatment_history (patientid, hospitalid, drugid, dose) VALUES (:patientid, :hospitalid, :drugid, :dose)', $params);
        return $result;
    }
    public function set_patient_risk($id, $hospitalid, $risk)
    {
        $params = [
            'id'         => $id,
            'risk'       => $risk,
            'hospitalid' => $hospitalid,
        ];
        $result = $this->db->query('UPDATE patients_database SET risk =:risk WHERE id =:id AND hospitalid = :hospitalid', $params);
        return $result;
    }
    public function set_patient_lab_data($patientid, $lab_data)
    {
        $params = [
            'patientid' => $patientid,
            'lab_data' => $lab_data,
        ];
        $result = $this->db->query('INSERT INTO lab_data(patientid, blood_type) VALUES (:patientid, :lab_data)', $params);
        return $result;
    }

}
