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
        $db = DB::DB();
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if ($user) {
            unset($user->password);
            return $user;
        }

        throw new Exception("User not found by given id");
    }
}