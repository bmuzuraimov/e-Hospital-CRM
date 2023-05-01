<?php
namespace application\models;

use application\core\Model;

class Main extends Model
{
    public function send_feedback($phone, $message, $ip_address)
    {
        $params   = [
            'phone'    => $phone,
            'message' => $message,
            'ip_address'    => $ip_address,
        ];
        $result = $this->db->query('INSERT INTO inbox(phone, message, ip_address) VALUES (:phone, :message, :ip_address)', $params);
        return $result;
    }
    public function unsent_sms()
    {
        $result = $this->db->row_no_num('SELECT `id`, `carrier`, `phone`, `sms` FROM `message` WHERE sent=0 ORDER BY id ASC LIMIT 1');
        return $result;
    }
    public function send_sms($id)
    {

        $params = [
            'id' => $id,
        ];
        $this->db->query('UPDATE message SET sent = 1, date = now() WHERE id = :id', $params);
    }
}
