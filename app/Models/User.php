<?php

namespace App\Models;

use App\DB\Model;
use App\Core\DB;
use PDO;

class User extends Model
{
    private $id;
    private $email;
    private $password;

    public function __construct($id, $email, $password)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public static function add(array $params): int
    {
        $db = DB::DB();

        return $db->lastInsertId();
    }

    public static function remove(int $id): bool
    {
        $db = DB::DB();

        return true;
    }

    private function getUserByEmail($email)
    {
        $db = DB::DB();
        $stmt = $db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function getUserById($id)
    {
        $db = DB::DB();
        $stmt = $db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
