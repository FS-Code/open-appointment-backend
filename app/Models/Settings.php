<?php

namespace App\Models;

use App\Core\DB;
use App\DB\Model;

class Settings extends Model {

    public $db;

    public function __construct()
    {
        $this->db = DB::DB();
    }

    public function get($userId, $key, $default = null)
    {
        $stmt = $this->db->prepare('SELECT * FROM settings WHERE `user_id` = :user_id and `key` = :key');
        $stmt->execute(['user_id' => $userId, 'key' => $key]);
        $result = $stmt->fetch();
        if(!$result){
            return $default;
        }

        return $result;
    }

    public function set($userId, $key, $value)
    {
        $stmt = $this->db->prepare('INSERT INTO settings (`user_id`, `key`, `value`) VALUES (:user_id, :key, :value)');
        $stmt->execute(['user_id' => $userId, 'key' => $key, 'value' => $value]);
        return true;
    }

    public function update($id, $value)
    {
        $stmt = $this->db->prepare('UPDATE settings SET `value` = :value WHERE id=:id');
        $stmt->execute(['id' => $id, 'value' => $value]);
        return true;
    }

    public function delete($id)
    {
        $this->db->prepare("DELETE FROM settings WHERE id=:id")
            ->execute(['id' => $id]);
        return true;
    }

}