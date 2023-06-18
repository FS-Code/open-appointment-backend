<?php

namespace App\Models;

use App\DB\Model;
use App\Core\DB;
use PDO;
use Exception;

class Settings extends Model{

    private $db = DB::DB();

    public function get($userId, $key, $default = null) {
        // $db = DB::DB();
        $sql = "SELECT 
                    `value` 
                FROM `settings` 
                WHERE `user_id` = ? 
                AND `deleted_at` IS NULL
                AND `key` = ?";

        $query = $this->db->prepare($sql);
        $query->execute([$userId, $key]);

        $result = [];
        if($query->rowCount()){
            $result = $query->fetch(PDO::FETCH_ASSOC);
        }

        return isset($result['value']) ? $result["value"] : $default;

    }

    public function set($user_id,$key,$value){
        $query = "INSERT INTO `settings`
                (`user_id`,`key`,`value`)
                VALUES 
                (?,?,?)";
        
        $params = [
            "user_id" => [$user_id,PDO::PARAM_INT],
            "key" => [$key,PDO::PARAM_STR],
            "value" => [$value,PDO::PARAM_STR]
        ];

        $last_inserted_id = DB::exeSQL($query,$params);

        return $last_inserted_id;

    }


    public function delete($user_id,$key,$soft = false){
        $query = $soft ? "UPDATE `settings` SET `deleted_at` =  NOW() WHERE `user_id` = ? AND `key` = ? " :  "DELETE FROM `settings` WHERE `user_id` = ? AND `key` = ? ";

        $params = [
            "user_id" => [$user_id,PDO::PARAM_INT],
            "key" => [$key,PDO::PARAM_STR],
        ];

        $deleted_id = DB::exeSQL($query,$params);

        return $deleted_id;
    }

     
}
