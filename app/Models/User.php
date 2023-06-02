<?php

namespace App\Models;

use App\DB\Model;

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

        $query = "Update user SET password = :password WHERE id = :id";

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