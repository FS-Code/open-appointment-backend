<?php

namespace App\Models;

use App\DB\Model;
use Exception;
use PDO;
use App\Core\DB;

class User extends Model
{
    public static function add(array $params): int
    {
        return 0;
    }

    public static function remove(int $id): bool
    {
        return true;
    }

    public static function getUserById(int $id): object
    {
        $db       = DB::DB();
        $query    = "SELECT id, email FROM user WHERE id= :id";
        $prepared = $db->prepare($query);
        $prepared->bindParam(":id", $id, PDO::PARAM_INT);
        $prepared->execute();

        $result = $prepared->fetchObject();
        
        if (!$result) {
            throw new Exception("User not found by given id");
        }
        
        return $result;
    }


}