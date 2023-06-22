<?php

namespace App\Models;

use App\DB\Model;
use App\Core\DB;

class Customer extends Model
{
    private int $id;
    private string $name;
    private string $email;

    public function __construct( int|null $id = null )
    {
        if ( ! empty( $id ) ) {
            $statement = DB::DB()->prepare( "SELECT name, email FROM customers WHERE id = :id" );
            $statement->bindParam( ':id', $id );
            $statement->execute();
            $result = $statement->fetch( \PDO::FETCH_OBJ );

            if ( $result ) {
                $this->id = $id;
                $this->name = $result->name;
                $this->email = $result->email;
            }
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName (): string
    {
        return $this->name;
    }

    public function setName (string $name): void
    {
        $this->name = $name;
    }

    public function getEmail (): string
    {
        return $this->email;
    }

    public function setEmail (string $email): void
    {
        $this->email = $email;
    }

    public function save():void
    {
        if (!!$this->id) {
            $this->update();
        } else {
            $this->insert();
        }
    }

    private function update():void
    {
        $statement = DB::DB()->prepare( "UPDATE customers SET name = :name, email = :email WHERE id = :id" );
        $statement->bindParam(':name', $this->name);
        $statement->bindParam(':email', $this->email);
        $statement->bindParam(':id', $this->id);
        $statement->execute();
    }

    private function insert (): void
    {
        $statement = DB::DB()->prepare( "INSERT INTO customers (name, email) VALUES (:name, :email)" );
        $statement->bindParam(':name', $this->name);
        $statement->bindParam(':email', $this->email);
        $statement->execute();

        $this->id = DB::DB()->lastInsertId();
    }

    private function delete (): void
    {
        if (!!$this->id) {
            $statement = DB::DB()->prepare( "DELETE FROM customers WHERE id = :id" );
            $statement->bindParam(':id', $this->id);
            $statement->execute();
        }
    }
}