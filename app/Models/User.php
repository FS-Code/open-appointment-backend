<?php

namespace App\Models;

use App\DB\Model;
use App\Core\DB;
use PDO;
use Exception;

class User extends Model
{
    public static function add( array $params ):int
    {
        return 0;
    }

    public static function remove( int $id ):bool
    {
        return true;
    }

    public static function getUserByLoginPass(string $email, string $password): object
    {
        $query = "SELECT * FROM user WHERE email = :email";
        $stmt = DB::DB()->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$user || $user->password !== $password) {
            throw new Exception('User not found by given credentials');
        }   

        unset($user->password);

        return $user;
    }
}