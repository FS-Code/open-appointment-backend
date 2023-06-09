<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Request;
use App\Core\Response;
use App\Helpers\AuthHelper;

class LoginController
{
    public function login()
    {
        $email = Request::post('email');
        $password = Request::post('password');

        $user = (new User())->getUserByEmail($email);

        if (!$user || !AuthHelper::verifyPassword($password, $user['password'])) {
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
