<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Request;
use App\Core\Response;
use App\Helpers\AuthHelper;

class UserController
{
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

		if ( ! preg_match( $password_regex, $password ) )
		{
			Response::setStatusBadRequest();
			return [ 'error' => 'Password does not meet requirements. The length should be at least 8 character. Please, use a-z, A-Z, numbers and at least one special character.' ];
		}

		$hashedPassword = password_hash( $password, PASSWORD_DEFAULT );

		try
		{
			$userId = User::createUser( $email, $hashedPassword );
			Response::setStatusCreated();
		}
		catch ( \Exception $e )
		{
			Response::setStatusConflict();
			return ['error' => $e->getMessage() ];
		}

		$jwt = AuthHelper::generateJWT( [ "userId" => $userId, "exp" => time() + 60 * 60 * 24 * 7 ] );
		setcookie( 'auth_token', $jwt );

		return [ 'user' => [ 'id' => $userId, 'email' => $email ] ];
	}
}
