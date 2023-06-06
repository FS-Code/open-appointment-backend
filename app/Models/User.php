<?php

namespace App\Models;

use App\DB\Model;
use App\Core\DB;
use PDO;
use Exception;

class User extends Model
{
    public static function createUser(string $email, string $hashedPassword): int
    {
        // check if user already exists
        $query = "SELECT * FROM user WHERE email = :email";
        $stmt = DB::DB()->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);
    
        if ($user) {
            throw new Exception('This user is already exists');
        }  
    
        // insert user into the database
        $query = "INSERT INTO user (email, password) VALUES (:email, :password)";
        $stmt = DB::DB()->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();
        
        return DB::DB()->lastInsertId();
    }
    
    

    public static function add( array $params ):int
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

    /**
     * @throws \Exception
     */
    public static function updateUserPassword(
        int $id,
        string $password
    ):void
    {
        //Assuming DB() creates the connection;

        $db = \App\Core\DB::DB();

        //SQL Query;

        $query = "UPDATE user SET password = :password WHERE id = :id";

        $statement = $db->prepare( $query );

        //Binding parameters;

        $statement->bindParam( ':id', $id );
        $statement->bindParam( ':password', $password);

        $statement->execute();

        //Exception handling;

        if ($statement->rowCount() == 0) {
            throw new \Exception("User not found!");
        }
    }

    public static function getUserByLoginPass(string $email, string $password): object
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = DB::DB()->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$user) {
            throw new Exception('User not found by given credentials');
        }   

        // Verify the password
        if (!password_verify($password, $user->password)) {
            throw new Exception('Invalid password');
        }

        unset($user->password);

        return $user;
    }
}