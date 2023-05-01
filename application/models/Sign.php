<?php
namespace application\models;

use application\core\Model;

class Sign extends Model
{
    public function isExist($userid, $isAdmin)
    {
        $params = [
            'userid'  => $userid,
            'service' => $isAdmin,
        ];
        $result = $this->db->row('SELECT EXISTS(SELECT * FROM user_sessions WHERE userid = :userid AND service = :service)', $params);
        return $result[0];
    }

    public function setKey($userid, $keyAuth, $isAdmin, $log_ip)
    {
        $params = [
            'userid'  => $userid,
            'keyAuth' => $keyAuth,
            'service' => $isAdmin,
            'log_ip'  => $log_ip,
        ];
        $result = $this->db->query('INSERT INTO user_sessions (service, userid, authKey, log_ip) VALUES (:service, :userid, :keyAuth, :log_ip)', $params);
        return $result;
    }

    public function updateKey($userid, $keyAuth, $service, $log_ip)
    {
//both
        $params = [
            'service' => $service,
            'keyAuth' => $keyAuth,
            'userid'  => $userid,
            'log_ip'  => $log_ip,
        ];
        $result = $this->db->query('UPDATE user_sessions SET service = :service, authKey = :keyAuth, log_ip = :log_ip WHERE userid = :userid', $params);
        return $result;
    }
    public function isValid($hospitalid)
    {
        $params = [
            'hospitalid' => $hospitalid,
        ];
        $result = $this->db->row('SELECT valid FROM hospitals_database WHERE id = :hospitalid', $params);
        return $result;
    }
    public function user_exists($passport, $phone)
    {
        $params = [
            'passport' => $passport,
            'phone' => $phone,
        ];
        $result = $this->db->row('SELECT EXISTS(SELECT * FROM doctor_users WHERE passport = :passport OR phone = :phone)', $params);
        return $result[0];
    }
    public function add_hospital($name, $address, $city, $oblast, $position, $tunits, $passport, $phone, $temp_password, $ip)
    {
        $params = [
            'name'    => $name,
            'address' => $address,
            'city'    => $city,
            'oblast'  => $oblast,
            'tunits'  => $tunits,
            'aunits'  => $tunits,
            'ip'      => $ip,
        ];
        $this->db->query('INSERT INTO hospitals_database (name, address, city, oblast, tunits, aunits, ip) VALUES (:name, :address, :city, :oblast, :tunits, :aunits, :ip)', $params);

        $hospitalid = $this->db->row('SELECT id FROM hospitals_database ORDER BY id DESC LIMIT 1');
        $password   = password_hash($temp_password, PASSWORD_DEFAULT);
        $params     = [
            'hospitalid'    => $hospitalid['id'],
            'passport'      => $passport,
            'phone'         => $phone,
            'position'      => $position,
            'temp_password' => $temp_password,
            'password'      => $password,
        ];
        $this->db->query('INSERT INTO doctor_users (hospitalid, isHead, passport, phone, position, temp_password, password) VALUES (:hospitalid, 1, :passport, :phone, :position, :temp_password, :password)', $params);
    }
}
