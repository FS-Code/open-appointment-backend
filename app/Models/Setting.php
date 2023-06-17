<?php
namespace App\Models;

use App\DB\Model;
use App\Core\DB;
use PDO;

class Setting extends Model
{
    public static function get($userId, $key, $default = null)
    {
        $db = DB::DB();
        $query = "SELECT value FROM settings WHERE user_id = ? AND `key` = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$userId, $key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && isset($result['value'])) {
            return $result['value'];
        }

        return $default;
    }

    public static function set($userId, $key, $value)
    {
        $sql = "INSERT INTO settings (user_id, `key`, value) VALUES (?, ?, ?)";
        $valuesNTypes = [
            'user_id' => [$userId, \PDO::PARAM_INT],
            'key' => [$key, \PDO::PARAM_STR],
            'value' => [$value, \PDO::PARAM_STR]
        ];

        return DB::exeSQL($sql, $valuesNTypes);
    }

     public static function update($userId, $key, $value)
    {
        $sql = "UPDATE settings SET value = ? WHERE user_id = ? AND `key` = ?";
        $valuesNTypes = [
            'value' => [$value, \PDO::PARAM_STR],
            'user_id' => [$userId, \PDO::PARAM_INT],
            'key' => [$key, \PDO::PARAM_STR]
        ];

        return DB::exeSQL($sql, $valuesNTypes);
    }

    public static function delete($userId, $key)
    {
        $sql = "DELETE FROM settings WHERE user_id = ? AND `key` = ?";
        $valuesNTypes = [
            'user_id' => [$userId, \PDO::PARAM_INT],
            'key' => [$key, \PDO::PARAM_STR]
        ];

        return DB::exeSQL($sql, $valuesNTypes);
    }

}
