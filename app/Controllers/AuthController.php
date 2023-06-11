<?php

namespace App\Controllers;

use App\Core\Response;
use App\Core\Request;
use App\Models\User;
use App\Helpers\AuthHelper;

class AuthController
{
    public function login()
    {
        $email    = Request::post('email');
        $password = Request::post('password');

        try {

            $user = User::getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {

                $token = AuthHelper::generateJWT([
                    'id' => $user['id'],
                    'email' => $user['email'],
                ]);

                $responseData = [
                    'user' => [
                        'id' => $user['id'],
                        'email' => $user['email'],
                    ],
                    'token' => $token,
                ];

                Response::setStatusOk();
                Response::json($responseData);
            } else {

                Response::setStatusBadRequest();
                Response::json(['error' => 'Email or password is incorrect']);
            }
        } catch (Exception $e) {
            // Exception occurred
            Response::setStatusBadRequest();
            Response::json(['error' => $e->getMessage()]);
        }
    }
}