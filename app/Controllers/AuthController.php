<?php

namespace App\Controllers;

use App\Core\Response;
use App\Core\Request;
use App\Models\User;
use App\Helpers\AuthHelper;
use Exception;

class AuthController
{
    public static function login(): array
    {
        $email    = Request::post('email');
        $password = Request::post('password');

        try {
            if (empty( $email ) || empty($password)) {
                throw new Exception("Email or password is incorrect");
            }

            $user = User::getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {

                $expiresAt = time() + 60 * 60 * 24 * 7;

                $token = AuthHelper::generateJWT([
                    'id' => $user['id'],
                    'exp' => $expiresAt,
                ]);

                setcookie('token', $token, $expiresAt);

                Response::setStatusOk();

                return [
                    'user' => [
                        'id' => $user['id'],
                        'email' => $user['email'],
                    ]
                ];
            } else {
                throw new Exception("Email or password is incorrect");
            }
        } catch (Exception $e) {
            Response::setStatusBadRequest();

            return ['error' => $e->getMessage()];
        }
    }
}