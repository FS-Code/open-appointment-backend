<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\DB;
use PDO;

class LoginController
{
    public function login(Request $request)
    {
        $email = Request::post('email');
        $password = Request::post('password');

        $user = $this->getUserByEmail($email);

        if (!$user) {
            return Response::json([
                'error' => 'Email or password is not correct'
            ]);
        }

        if (!password_verify($password, $user['password'])) {
            return Response::json([
                'error' => 'Email or password is not correct'
            ]);
        }

        return Response::json([
            'data' => [
                'user' => [
                    'id' => $user['id'],
                    'email' => $user['email']
                ]
            ]
        ]);
    }

    private function getUserByEmail($email)
    {
        $db = DB::getInstance();
        $stmt = $db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
