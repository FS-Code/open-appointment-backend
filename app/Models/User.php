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
