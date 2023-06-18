<?php

namespace App\Models;

use App\DB\Model;
use App\Core\DB;
use PDO;
use Exception;

class Settings extends Model
{
    public static function get($userId, $key, $default = null)
    {
        $query = "SELECT value FROM settings WHERE user_id = :user_id AND key_name = :key_name";
        $stmt = DB::DB()->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':key_name', $key, PDO::PARAM_STR);
        $stmt->execute();

        $setting = $stmt->fetch(PDO::FETCH_OBJ);

        return $setting ? $setting->value : $default;
    }

    public static function set($userId, $key, $value)
    {
        $query = "INSERT INTO settings (user_id, key_name, value) VALUES (:user_id, :key_name, :value) ON DUPLICATE KEY UPDATE value = :new_value";
        $stmt = DB::DB()->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':key_name', $key, PDO::PARAM_STR);
        $stmt->bindParam(':value', $value, PDO::PARAM_STR);
        $stmt->bindParam(':new_value', $value, PDO::PARAM_STR);
        $stmt->execute();
    }

    public static function delete($userId, $key)
    {
        $query = "DELETE FROM settings WHERE user_id = :user_id AND key_name = :key_name";
        $stmt = DB::DB()->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':key_name', $key, PDO::PARAM_STR);
        $stmt->execute();
    }
}
