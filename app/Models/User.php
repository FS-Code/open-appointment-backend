<?php

namespace App\Models;

use App\DB\Model;

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

    // public static function hashPassword(string $password): string
    // {
    //     return password_hash($password, PASSWORD_DEFAULT);
    // }

    public static function add(array $params): int
    {
        return 0;
    }

    public static function remove(int $id): bool
    {
        return true;
    }


}