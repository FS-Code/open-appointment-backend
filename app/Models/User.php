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
        // Check if user with given email already exists
        $existingUser = self::getUserByEmail($email);
        if ($existingUser) {
            throw new \Exception('This user already exists.');
        }

            // Insert the new user into the database
            $query = "INSERT INTO users (email, password) VALUES (:email, :password)";
            $params = [
                'email' => $email,
                'password' => $hashedPassword,
            ];
            $connection = DB::DB(); // Get the existing PDO connection
            $stmt = $connection->prepare($query);
            $stmt->execute($params);

            // Return the ID of the newly created user
            return $connection->lastInsertId();
        
    }

    public static function getUserByEmail(string $email)
    {
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $params = [
            'email' => $email,
        ];
        $connection = DB::DB(); // Get the existing PDO connection
        $stmt = $connection->prepare($query);
        $stmt->execute($params);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

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
        $query = "SELECT * FROM user WHERE email = :email AND password = :password";
        $stmt = DB::DB()->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$user) {
            throw new Exception('User not found by given credentials');
        }   

        unset($user->password);

        return $user;
    }
}
