<?php

namespace App\Controllers;

use App\Core\Response;
use App\Core\Request;
use App\Models\User;
use App\Helpers\AuthHelper;
use Exception;


class AuthController
{
    public static function login()
    {
        $email    = Request::post('email');
        $password = Request::post('password');

        try {

            $user = User::getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {

                $token = AuthHelper::generateJWT([
                    'id' => $user['id'],
                ]);

                $responseData = [
                    'user' => [
                        'id' => $user['id'],
                        'email' => $user['email'],
                    ]
                ];

                setcookie('token', $token, 0, '/');

                Response::setStatusOk();
                return $responseData;

            } else {

                Response::setStatusBadRequest();
                throw new Exception("Email or password is incorrect");
            }
        } catch (Exception $e) {
            // Exception occurred
            Response::setStatusBadRequest();
            return ['error' => $e->getMessage()];
        }
    }
}