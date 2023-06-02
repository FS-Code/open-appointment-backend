<?php

namespace App\Models;

use PDO;
use Exception;
use App\Core\DB;
use App\DB\Model;
use PDOException;


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

    public function updateUserEmail( int $id, string $email): void
    {
        try {
            $connection = DB::DB();
            $checkUser = $connection->prepare("SELECT id FROM users WHERE id = :id");
            $checkUser->bindParam(":id",$id);
            $checkUser->execute();
            $checkUser = $checkUser->fetch();
            
            if($checkUser){
                $checkUserEmail = $connection->prepare("SELECT email FROM users WHERE email=:email");
                $checkUserEmail->bindParam(":email",$email);
                $checkUserEmail->execute();
                $checkUserEmail = $checkUserEmail->fetch();
    
                if (!$checkUserEmail){
                    $userEmailUpdate = $connection->prepare("UPDATE users SET email = :email WHERE id = :id");
                    $userEmailUpdate->bindParam(":email",$email);
                    $userEmailUpdate->bindParam(":id",$id);
                    $userEmailUpdate->execute();
    
                } else {
                    throw new Exception("This email already exists");
                }
                
            } else {
                throw new Exception("User not found");
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}