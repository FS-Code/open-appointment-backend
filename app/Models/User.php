<?php

namespace App\Models;

use App\DB\Model;

use PDO;
use Exception;
use App\Core\DB;

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


    function getUserByLoginPass(string $email, string $password): object {

        $db = DB::DB(); 
        
        
        $sql = "SELECT 
                     `id`,
                     `email`,
                     `password` 
                 FROM users 
                 WHERE email = :email
                 LIMIT 1"; #if email is not unique

        $query = $db->prepare($sql);

        $query->bindParam(':email', $email);

        $query->execute();
        
        if(!$query->rowCount()){
            throw new Exception('User not found by given credentials');
        }

        $user = $query->fetch(PDO::FETCH_OBJ);
        
        if (!password_verify($password,$user->password)) {
            throw new Exception('User not found by given credentials');
        }        
        
        unset($user->password);
        
        return $user;
    }
}