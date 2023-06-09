<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\User;

class LoginController
{
    public function login(Request $request)
    {
        $email = $request->post('email');
        $password = $request->post('password');

        $user = new User(null, $email, $password);
        $existingUser = $user->getUserByEmail($email);

        if (!$existingUser) {
            return Response::json([
                'error' => 'Email or password is not correct'
            ]);
        }

        if (!password_verify($password, $existingUser['password'])) {
            return Response::json([
                'error' => 'Email or password is not correct'
            ]);
        }

        return Response::json([
            'data' => [
                'user' => [
                    'id' => $existingUser['id'],
                    'email' => $existingUser['email']
                ]
            ]
        ]);
    }
}
