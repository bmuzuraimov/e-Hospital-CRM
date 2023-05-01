<?php
namespace application\core;

use application\lib\Db;

abstract class Model
{
    public $db;

    public function __construct()
    {
        $this->db = new Db;
    }
    public function get_random_passwd()
    {
        $alphabet    = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass        = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 10; $i++) {
            $n      = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    public function get_client_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    public function delete_key($userid, $service)
    {
        $params = [
            'userid'  => $userid,
            'service' => $service,
        ];
        $result = $this->db->query('DELETE FROM user_sessions WHERE userid = :userid AND service = :service', $params);
        return $result;
    }

    public function get_key($userid, $service)
    {
        $params = [
            'userid'  => $userid,
            'service' => $service,
        ];
        $result = $this->db->row('SELECT userid, service, authKey FROM user_sessions WHERE userid = :userid AND service = :service', $params);
        return $result;
    }
    public function get_doctor_username($id)
    {
        $params = [
            'id' => $id,
        ];
        $result = $this->db->row('SELECT fname, lname, hospitalid FROM doctor_users WHERE id =:id', $params);
        return $result;
    }
    public function get_analytics($hospitalid = null)
    {
        $analytics = array();
        $today     = date("Y-m-d");
        if (is_null($hospitalid)) {
            //---------GENERAL ANALYTICS---------------
            $gen_total  = $this->db->row('SELECT COUNT(*) FROM patients_database');
            $gen_total  = ($gen_total !== false) ? $gen_total[0] : 0;
            $gen_healed = $this->db->row('SELECT COUNT(*) FROM patients_database WHERE status = "recovered"');
            $gen_healed = ($gen_healed !== false) ? $gen_healed[0] : 0;
            $gen_dead   = $this->db->row('SELECT COUNT(*) FROM patients_database WHERE status = "dead"');
            $gen_dead   = ($gen_dead !== false) ? $gen_dead[0] : 0;

            //---------DAILY ANALYTICS---------------

            $params = [
                'today' => $today,
            ];
            $daily_total  = $this->db->row('SELECT COUNT(*) FROM patients_database WHERE sickDate = :today', $params);
            $daily_total  = ($daily_total !== false) ? $daily_total[0] : 0;
            $daily_healed = $this->db->row('SELECT COUNT(*) FROM patients_database WHERE status = "recovered" AND recDate = :today', $params);
            $daily_healed = ($daily_healed !== false) ? $daily_healed[0] : 0;
            $daily_dead   = $this->db->row('SELECT COUNT(*) FROM patients_database WHERE status = "dead" AND dDate = :today', $params);
            $daily_dead   = ($daily_dead !== false) ? $daily_dead[0] : 0;

            $analytics = [
                'gen_total'  => $gen_total,
                'gen_healed' => $gen_healed,
                'gen_dead'   => $gen_dead,
                'day_total'  => $daily_total,
                'day_healed' => $daily_healed,
                'day_dead'   => $daily_dead,
            ];

        } else {

            //---------GENERAL ANALYTICS---------------
            $params = [
                'hospitalid' => $hospitalid,
            ];
            $gen_total  = $this->db->row('SELECT COUNT(*) FROM patients_database WHERE hospitalid = :hospitalid', $params);
            $gen_total  = ($gen_total !== false) ? $gen_total[0] : 0;
            $gen_healed = $this->db->row('SELECT COUNT(*) FROM patients_database WHERE hospitalid = :hospitalid AND status = "recovered"', $params);
            $gen_healed = ($gen_healed !== false) ? $gen_healed[0] : 0;
            $gen_dead   = $this->db->row('SELECT COUNT(*) FROM patients_database WHERE hospitalid = :hospitalid AND status = "dead"', $params);
            $gen_dead   = ($gen_dead !== false) ? $gen_dead[0] : 0;

            //---------DAILY ANALYTICS---------------

            $params = [
                'hospitalid' => $hospitalid,
                'today'      => $today,
            ];
            $daily_total  = $this->db->row('SELECT COUNT(*) FROM patients_database WHERE hospitalid = :hospitalid AND sickDate = :today', $params);
            $daily_total  = ($daily_total !== false) ? $daily_total[0] : 0;
            $daily_healed = $this->db->row('SELECT COUNT(*) FROM patients_database WHERE hospitalid = :hospitalid AND status = "recovered" AND recDate = :today', $params);
            $daily_healed = ($daily_healed !== false) ? $daily_healed[0] : 0;
            $daily_dead   = $this->db->row('SELECT COUNT(*) FROM patients_database WHERE hospitalid = :hospitalid AND status = "dead" AND dDate = :today', $params);
            $daily_dead   = ($daily_dead !== false) ? $daily_dead[0] : 0;

            //---------UNITS ANALYTICS---------------
            //---------UPDATE UNITS------------------
            $params = [
                'hospitalid' => $hospitalid,
                'today'      => $today,
            ];
            $aunits = $this->db->countRows('SELECT * FROM hospital_units WHERE hospitalid = :hospitalid AND :today BETWEEN fromDate AND toDate', $params);
            $params = [
                'id'     => $hospitalid,
                'aunits' => $aunits,
            ];
            $this->db->query('UPDATE hospitals_database SET aunits = (tunits - :aunits) WHERE id = :id', $params);
            //--------QUERY UNITS-----------------
            $params = [
                'hospitalid' => $hospitalid,
            ];
            $units  = $this->db->row('SELECT tunits, aunits FROM hospitals_database WHERE id = :hospitalid', $params);
            $tunits = (!empty($units)) ? $units['tunits'] : 0;
            $aunits = (!empty($units)) ? $units['aunits'] : 0;

            $analytics = [
                'gen_total'  => $gen_total,
                'gen_healed' => $gen_healed,
                'gen_dead'   => $gen_dead,
                'day_total'  => $daily_total,
                'day_healed' => $daily_healed,
                'day_dead'   => $daily_dead,
                'tunits'     => $tunits,
                'aunits'     => $aunits,
            ];
        }
        return $analytics;
    }
    public function get_published_recent_posts()
    {
        $result = $this->db->rows('SELECT * FROM articles WHERE published = 1 ORDER BY date DESC LIMIT 10');
        return $result;
    }

    public function get_published_popular_posts()
    {
        $recent_posts = '';
        $params       = [
            'recent_posts' => 'SELECT id, published, category, visited, date, author, profile_img, title, text FROM articles WHERE published = 1 ORDER BY date DESC LIMIT 10',
        ];
        $result = $this->db->rows('SELECT * FROM articles WHERE published = 1 AND id NOT IN (:recent_posts) ORDER BY visited DESC LIMIT 10', $params);
        return $result;
    }

    public function get_post($id)
    {
        $params = [
            'id' => $id,
        ];
        $result = $this->db->row('SELECT id, published, category, visited, date, author, title, text FROM articles WHERE id = :id', $params);
        return $result;
    }

    public function visit_post($id, $current_ip)
    {
        $params = [
            'id' => $id,
        ];
        $ip_addresses  = $this->db->row('SELECT readers FROM articles WHERE id = :id', $params);
        $readers = explode(",", $ip_addresses['readers']);
        $length  = count($readers);
        if ($length != 0) {
            $isIpExists = false;
            for ($index = 0; $index < $length; $index++) {
                $ip_address = str_replace(' ', '', $readers[$index]);
                if ($current_ip == $ip_address) {
                    $isIpExists = true;
                    break;
                }
            }
            if (!$isIpExists) {
                $new_ip_addresses = $ip_addresses['readers'].",".$current_ip;
                $params = [
                    'id' => $id,
                    'readers' => $new_ip_addresses,
                ];
                $result = $this->db->row('UPDATE articles SET visited = (visited+1), readers = :readers WHERE id = :id', $params);
            }
        }
    }
    public function get_myhospital_messages($hospitalid)
    {
        $params = [
            'hospitalid' => $hospitalid,
        ];
        $result = $this->db->rows('SELECT * FROM (SELECT internal_chat.userid, internal_chat.isHead, internal_chat.message, CONCAT(doctor_users.fname, " ", doctor_users.lname) as sender, doctor_users.sex, date FROM internal_chat LEFT JOIN doctor_users ON internal_chat.userid = doctor_users.id WHERE internal_chat.hospitalid = :hospitalid ORDER BY internal_chat.date DESC LIMIT 20) sub ORDER BY date ASC', $params);
        return $result;
    }

    public function send_message($hospitalid, $userid, $isHead, $message)
    {
        $params = [
            'hospitalid' => $hospitalid,
            'userid'     => $userid,
            'isHead'     => $isHead,
            'message'    => $message,
        ];
        $result = $this->db->query('INSERT INTO internal_chat (hospitalid, userid, isHead, message) VALUES (:hospitalid, :userid, :isHead, :message)', $params);
        return $result;
    }

    public function date_to_age($dateofbirth)
    {
        $today = date("Y-m-d");
        $diff  = date_diff(date_create($dateofbirth), date_create($today));
        $diff  = $diff->format('%y');
        return $diff;
    }
    public function get_risk($patientid)
    {
        $params = [
            'patientid' => $patientid,
        ];
        $result = $this->db->row('SELECT * FROM illness_data WHERE patient = :patientid', $params);
        return $result;
    }
    public function get_diagnosed_patients($hospitalid)
    {
        $params = [
            'status'   => 'waiting',
            'hospital' => $hospitalid,
        ];
        $result = $this->db->rows('SELECT * FROM patients_database WHERE hospitalid = :hospital AND risk IS NOT NULL AND status=:status ORDER BY risk DESC, age DESC', $params);
        return $result;
    }
    public function get_patient_health($patientid)
    {
        $params = [
            'patientid' => $patientid,
        ];
        $result = $this->db->rows('SELECT * FROM (SELECT patientid, hospitalid, improvement, data FROM patients_condition WHERE patientid = :patientid ORDER BY data DESC) sub ORDER BY data ASC', $params);
        return $result;
    }
    public function get_matching_patient($patientid)
    {
        $today  = date("Y-m-d");
        $params = [
            'patientid' => $patientid,
            'today'     => $today,
        ];
        $result = $this->db->rows('SELECT patients_database.id, patients_database.age, patients_database.sex, ld.blood_type, patients_database.symptoms, illness_data.illness, illness_data.breath, illness_data.temperature, illness_data.weakness FROM patients_database INNER JOIN hospital_units ON patients_database.id = hospital_units.patientid INNER JOIN illness_data ON patients_database.id = illness_data.patient LEFT JOIN lab_data as ld ON patients_database.id = ld.patientid WHERE hospital_units.toDate < :today AND patients_database.id != :patientid', $params);
        return $result;
    }
    public function treatment_days($patientid, $hospitalid)
    {
        $params = [
            'patientid'  => $patientid,
            'hospitalid' => $hospitalid,
        ];
        $result = $this->db->row('SELECT count( DISTINCT(`date`) ) FROM treatment_history WHERE hospitalid = :hospitalid AND patientid = :patientid', $params);
        return $result;
    }
    public function get_treatments($patientid, $hospitalid)
    {
        $params = [
            'hospitalid' => $hospitalid,
            'patientid'  => $patientid,
        ];
        $result = $this->db->rows('SELECT pharmacy_database.name, pharmacy_database.purpose, treatment_history.dose, treatment_history.date, treatment_history.time FROM treatment_history INNER JOIN pharmacy_database ON treatment_history.drugid = pharmacy_database.id WHERE treatment_history.hospitalid = :hospitalid AND treatment_history.patientid = :patientid ORDER BY date,time', $params);
        return $result;
    }
    public function get_plan_treatment_days($patientid)
    {
        $params = [
            'patientid' => $patientid,
        ];
        $result = $this->db->row('SELECT COUNT(DISTINCT(day)) FROM treatment_recomendation WHERE patientid = :patientid', $params);
        return $result[0];
    }
    public function get_plan_treatments($patientid, $hospitalid, $day = null)
    {
        if (!is_null($day)) {
            $time = date('H:i', time());
            if ($time > '05:00' && $time < '12:00') {
                $fromTime = 5;
                $toTime   = 12;
            } elseif ($time >= '12:00' && $time < '18:00') {
                $fromTime = 12;
                $toTime   = 16;
            } elseif ($time >= '18:00' && $time < '24:00') {
                $fromTime = 18;
                $toTime   = 24;
            } else {
                $fromTime = false;
                $toTime   = false;
            }
            $params = [
                'hospitalid' => $hospitalid,
                'patientid'  => $patientid,
                'day'        => $day,
                'fromTime'   => $fromTime,
                'toTime'     => $toTime,
            ];
            $result = $this->db->rows('SELECT pharmacy_database.name, pharmacy_database.purpose, treatment_recomendation.dose, treatment_recomendation.day, treatment_recomendation.time FROM treatment_recomendation INNER JOIN pharmacy_database ON treatment_recomendation.drugid = pharmacy_database.id WHERE treatment_recomendation.hospitalid = :hospitalid AND treatment_recomendation.patientid = :patientid AND day = :day AND time BETWEEN :fromTime AND :toTime', $params);
        } else {
            $params = [
                'hospitalid' => $hospitalid,
                'patientid'  => $patientid,
            ];
            $result = $this->db->rows('SELECT pharmacy_database.name, pharmacy_database.purpose, treatment_recomendation.dose, treatment_recomendation.day, treatment_recomendation.time FROM treatment_recomendation INNER JOIN pharmacy_database ON treatment_recomendation.drugid = pharmacy_database.id WHERE treatment_recomendation.hospitalid = :hospitalid AND treatment_recomendation.patientid = :patientid ORDER BY treatment_recomendation.day, treatment_recomendation.time', $params);
        }
        return $result;
    }
    public function is_medicine_exists($medicine)
    {
        $params = [
            'medicine' => $medicine,
        ];
        $result = $this->db->row('SELECT id FROM pharmacy_database WHERE name = :medicine', $params);
        return $result['id'];
    }
    public function get_unit_info($patientid, $hospitalid)
    {
        $params = [
            'hospitalid' => $hospitalid,
            'patientid'  => $patientid,
        ];
        $result = $this->db->row('SELECT unit, fromDate, toDate, time FROM hospital_units WHERE hospitalid = :hospitalid AND patientid = :patientid', $params);
        return $result;
    }
    public function get_hospital_info($hospitalid = null, $doctorid = null)
    {
        if (!is_null($doctorid) && is_null($hospitalid)) {
            $params = [
                'doctorid' => $doctorid,
            ];
            $result = $this->db->row('SELECT doctor_users.hospitalid, hospitals_database.valid, hospitals_database.name, hospitals_database.address, hospitals_database.city, hospitals_database.oblast, hospitals_database.tunits, hospitals_database.aunits, doctor_users.fname, doctor_users.lname, doctor_users.passport, doctor_users.birthday, doctor_users.sex, doctor_users.phone, doctor_users.position, doctor_users.temp_password, doctor_users.logDate FROM hospitals_database INNER JOIN doctor_users ON hospitals_database.id = doctor_users.hospitalid WHERE doctor_users.id = :doctorid', $params);
        } elseif (!is_null($hospitalid) && is_null($doctorid)) {
            $params = [
                'hospitalid' => $hospitalid,
            ];
            $result = $this->db->row('SELECT valid, name, address, city, oblast, tunits, aunits FROM hospitals_database WHERE id = :hospitalid', $params);
        } else {
            $result = false;
        }
        return $result;
    }
    public function get_symptoms()
    {
        return $this->db->rows('SELECT name FROM symptoms ORDER BY occured DESC');
    }
    public function is_symptome_exists($name)
    {
        $params = [
            'name' => $name,
        ];
        $result = $this->db->row('SELECT 1 FROM symptoms WHERE name = :name', $params);
        return $result[1];
    }
    public function add_symptome($name)
    {
        $params = [
            'name' => $name,
        ];
        $this->db->query('INSERT INTO symptoms (name) VALUES (:name)', $params);
    }
    public function use_symptome($name)
    {
        $params = [
            'name' => $name,
        ];
        $this->db->query('UPDATE symptoms SET occured = (occured+1) WHERE name = :name', $params);
    }
    public function get_lab_data($patientid)
    {
        $params = [
            'patientid' => $patientid,
        ];
        $result = $this->db->row('SELECT blood_type FROM lab_data WHERE patientid = :patientid', $params);

    }
    public function get_patients_by_hospital($hospitalid)
    {
        $params = [
            'hospitalid' => $hospitalid,
        ];
        $result = $this->db->rows('SELECT patients_database.id, patients_database.fname, patients_database.lname, patients_database.age, patients_database.status, patients_database.risk, illness_data.id as illness_data FROM patients_database LEFT OUTER JOIN illness_data ON patients_database.id = illness_data.patient WHERE patients_database.hospitalid = :hospitalid ORDER BY patients_database.risk DESC, patients_database.age ASC', $params);
        return $result;
    }
    public function get_patient_info($patientid, $hospitalid)
    {
        $params = [
            'patientid'  => $patientid,
            'hospitalid' => $hospitalid,
        ];
        $result = $this->db->row('SELECT patient_info.*, ld.blood_type FROM patients_database as patient_info LEFT JOIN lab_data as ld ON patient_info.id = ld.patientid WHERE patient_info.hospitalid = :hospitalid AND patient_info.id = :patientid', $params);
        return $result;
    }
    public function set_drug($name, $purpose, $maxdose, $description)
    {
        $params = [
            'name'        => $name,
            'purpose'     => $purpose,
            'maxdose'     => $maxdose,
            'description' => $description,
        ];
        $result = $this->db->query('INSERT INTO pharmacy_database (name, efficiency, purpose, maxdose, description) VALUES (:name, 0, :purpose, :maxdose, :description)', $params);
        return $result;
    }
    public function get_drug($name = null, $purpose = null, $single = false)
    {
        if (!(is_null($name)) && (is_null($purpose)) && $single === false) {
            $params = [
                'name' => "$name%",
            ];
            $result = array();
            if ($name != "") {
                $result = $this->db->rows("SELECT * FROM pharmacy_database WHERE name LIKE :name ORDER BY name", $params);
            }
        } elseif (!(is_null($name)) && !(is_null($purpose)) && $single === true) {
            $params = [
                'name'    => $name,
                'purpose' => $purpose,
            ];
            $result = $this->db->row('SELECT id FROM pharmacy_database WHERE name=:name AND purpose=:purpose ORDER BY name', $params);
        } elseif ((is_null($name)) && !(is_null($purpose))) {
            $params = [
                'purpose' => $purpose,
            ];
            $result = $this->db->rows('SELECT * FROM pharmacy_database WHERE purpose=:purpose ORDER BY name', $params);
        } else {
            $result = $this->db->rows('SELECT * FROM pharmacy_database ORDER BY name');
        }
        return $result;
    }

    public function view_drug($drugid)
    {
        $params = [
            'drugid' => $drugid,
        ];
        $result = $this->db->row('SELECT * FROM pharmacy_database WHERE id = :drugid', $params);
        return $result;
    }

    public function set_procedure($name, $efficiency, $purpose, $maxdose, $description)
    {
        $params = [
            'name'        => $name,
            'efficiency'  => $efficiency,
            'purpose'     => $purpose,
            'maxdose'     => $maxdose,
            'description' => $description,
        ];
        $result = $this->db->query('INSERT INTO procedures (name, efficiency, purpose, maxdose, description) VALUES (:name, :efficiency, :purpose, :maxdose, :description)', $params);
        return $result;
    }
    public function get_procedures()
    {
        $result = $this->db->rows('SELECT * FROM procedures');
        return $result;
    }
    public function get_hospitalized_patients($hospitalid)
    {
        $today  = date("Y-m-d");
        $params = [
            'hospitalid' => $hospitalid,
            'today'      => $today,
        ];
        $result = $this->db->rows('SELECT patients_database.id, hospital_units.unit, patients_database.fname, patients_database.lname, patients_database.age, patients_database.risk, illness_data.id as illness_data, hospital_units.fromDate, hospital_units.toDate FROM patients_database INNER JOIN hospital_units ON patients_database.id = hospital_units.patientid LEFT OUTER JOIN illness_data ON patients_database.id = illness_data.patient WHERE hospital_units.hospitalid = :hospitalid AND :today BETWEEN hospital_units.fromDate AND hospital_units.toDate ORDER BY hospital_units.unit ASC', $params);
        return $result;
    }
    public function get_patients_by_status($sick, $hospitalid)
    {
        $params = [
            'hospitalid' => $hospitalid,
            'recover'    => $sick,
        ];
        $result = $this->db->rows('SELECT patients_database.id, patients_database.fname, patients_database.lname, patients_database.age, patients_database.status, patients_database.risk, illness_data.id as illness_data FROM patients_database LEFT OUTER JOIN illness_data ON patients_database.id = illness_data.patient WHERE patients_database.hospitalid = :hospitalid AND patients_database.status =:recover ORDER BY patients_database.risk DESC, patients_database.age ASC', $params);
        return $result;
    }
}
