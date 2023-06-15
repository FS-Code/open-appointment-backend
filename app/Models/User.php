<?php

namespace App\Models;

use App\DB\Model;
use App\Core\DB;
use PDO;
use Exception;

class User extends Model
{
    private int $id;
    private string $email;
    private string $password;

    /**
     * @throws Exception
     */
    public function __construct(int|null $id = null )
    {
        if ( ! empty( $id ) ) {
            $query = DB::DB()->prepare( "SELECT * FROM users WHERE id= :id" );
            $query->bindParam( ":id", $id, PDO::PARAM_INT );
            $query->execute();

            $user = $query->fetchObject();

            if ( !$user ) throw new Exception("User not found");

            $this->id = $id;
            $this->email = $user->email;
            $this->password = $user->password;
        }
    }

    public function getId (): int
    {
        return $this->id;
    }

    public function getEmail (): string {
        return $this->email;
    }

    public function getPassword (): string {
        return $this->password;
    }

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

    /**
     * @throws Exception
     */
    public static function getUserByEmail(string $email): User
    {
        $query = DB::DB()->prepare( "SELECT * FROM users WHERE email = :email" );
        $query->bindParam( ":email", $email, PDO::PARAM_STR );
        $query->execute();

        $user = $query->fetchObject();

        if ( !$user ) throw new Exception("User not found");

        return new User($user->id);
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

        $query = "UPDATE users SET password = :password WHERE id = :id";

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
}
