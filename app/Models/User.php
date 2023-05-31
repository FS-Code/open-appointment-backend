<?php

namespace App\Models;

use App\DB\Model;
use PDOException;

class User extends Model
{

    public static function createUser(string $email, string $password): int
    {
        // Check if user with given email already exists
        $existingUser = self::where('email', $email)->first();
        if ($existingUser) {
            throw new \Exception('This user already exists.');
        }

        try {
            // Insert the new user into the database
            $query = "INSERT INTO users (email, password) VALUES (:email, :password)";
            $params = [
                'email' => $email,
                'password' => $password,
            ];
            $connection = DB::DB(); // Get the existing PDO connection
            $stmt = $connection->prepare($query);
            $stmt->execute($params);

            // Return the ID of the newly created user
            return $connection->lastInsertId();
        } catch (PDOException $e) {
            throw new \Exception('Failed to create user: ' . $e->getMessage());
        }
    }


    public static function add( array $params ):int
    {
        return 0;
    }

    public static function remove( int $id ):bool
    {
        return true;
    }


}