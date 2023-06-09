<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Request;
use App\Core\Response;

class LoginController
{
    public function login()
    {
        $email = Request::post('email');
        $password = Request::post('password');

        $user = new User()->getUserByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            Response::setStatusBadRequest();
            return ['error' => 'Email or password is not correct'];
        }

        Response::setStatusOk();
        return [
            'data' => [
                'user' => [
                    'id' => $user['id'],
                    'email' => $user['email']
                ]
            ]
        ];
    }
}
