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

    /**
     * @throws \Exception
     */
    public static function register () : string | array
    {
        $email = Request::post( 'email' );
        $password = Request::post( 'password' );

        if ( ! filter_var( $email , FILTER_VALIDATE_EMAIL ) )
        {
            Response::setStatusBadRequest();
            return [ 'error' => 'Email is not correct.' ];
        }

        $password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*.-]).{8,}$/";

        if ( empty( $password ) || ! preg_match( $password_regex, $password ) )
        {
            Response::setStatusBadRequest();
            return [ 'error' => 'Password does not meet requirements. The length should be at least 8 character. Please, use a-z, A-Z, numbers and at least one special character.' ];
        }

        $hashedPassword = password_hash( $password, PASSWORD_DEFAULT );

        try
        {
            $userId = User::createUser( $email, $hashedPassword );
            $expiresAt = time() + 60 * 60 * 24 * 7;

            $jwt = AuthHelper::generateJWT( [ "userId" => $userId, "exp" => $expiresAt ] );

            setcookie( 'auth_token', $jwt, $expiresAt );
        }
        catch ( \Exception $e )
        {
            Response::setStatusConflict();
            return ['error' => $e->getMessage() ];
        }

        Response::setStatusCreated();
        return [ 'user' => [ 'id' => $userId, 'email' => $email ] ];
    }
}