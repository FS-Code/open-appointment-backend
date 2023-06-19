<?php

namespace App\Models;

use App\DB\Model;
use App\Core\DB;

class Customer extends Model
{
    private ?int $id;
    private ?string $name;
    private ?string $email;

    public function __construct( ?int $id = null )
    {
        $this->id = $id;
        if ($id != null) {
            $this->getCustomerFromDatabase();
        }
    }

    public function getId():?int
    {
        return $this->id;
    }

    public function getName():?string
    {
        return $this->name;
    }

    public function setName(?string $name):void
    {
        $this->name = $name;
    }

    public function getEmail():?string
    {
        return $this->email;
    }

    public function setEmail(?string $email):void
    {
        $this->email = $email;
    }

    private function save():void
    {
        if ($this->id !== null) {
            $this->update();
        } else {
            $this->insert();
        }
    }

    private function update():void
    {
        $db = DB::DB();
        $query = "UPDATE customer SET name = :name, email = :email WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindParam(':name', $this->name);
        $statement->bindParam(':email', $this->email);
        $statement->bindParam(':id', $this->id);
        $statement->execute();
    }

    private function insert(): void
    {
        $db = DB::DB();
        $query = "INSERT INTO customer (name, email) VALUES (:name, :email)";
        $statement = $db->prepare($query);
        $statement->bindParam(':name', $this->name);
        $statement->bindParam(':email', $this->email);
        $statement->execute();
        $this->id = $db->lastInsertId();
    }

    private function delete(): void
    {
        if ($this->id !== null) {
            $db = DB::DB();
            $query = "DELETE FROM customer WHERE id = :id";
            $statement = $db->prepare($query);
            $statement->bindParam(':id', $this->id);
            $statement->execute();
        }
    }

    private function getCustomerFromDatabase(): void
    {
        $db = DB::DB();
        $query = "SELECT name, email FROM customer WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindParam(':id', $this->id);
        $statement->execute();
        $result = $statement->fetch();
        if ($result) {
            $this->name = $result['name'];
            $this->email = $result['email'];
        }
    }
}