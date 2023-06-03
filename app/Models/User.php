<?php

namespace App\Models;

use App\DB\Model;
use Exception;
use App\Core\DB;
use PDO;
use PDOException;

class User extends Model
{
    public static function updateUserEmail(int $id, string $email): void
    {
        try {
            $db = DB::getConnection(); 
            $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $userExists = $stmt->fetchColumn();

            if (!$userExists) {
                throw new Exception('User not found');
            }

            $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE email = :email AND id != :id");
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $emailExists = $stmt->fetchColumn();

            if ($emailExists) {
                throw new Exception('This email is already in use');
            }

            $stmt = $db->prepare("UPDATE users SET email = :email WHERE id = :id");
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Database error: ' . $e->getMessage());
        }
    }
}
?>
