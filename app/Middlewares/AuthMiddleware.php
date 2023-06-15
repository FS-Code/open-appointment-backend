<?php

namespace App\Middlewares;

use App\Core\Middleware;
use App\Core\Request;
use App\Helpers\AuthHelper;
use App\Models\User;
use Exception;

class AuthMiddleware extends Middleware {
    /**
     * @throws Exception
     */
    public static function index (): void
    {
        $authToken = Request::cookie("auth_token");

        if (empty( $authToken )) throw new Exception("You don't have an access to this endpoint");

        $data = AuthHelper::readJWT( $authToken );

        if ( $data[ 'exp' ] < time() ) throw new Exception("Token is invalid");

        Request::$user = new User( $data['user_id'] );
    }
}