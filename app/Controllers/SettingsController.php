<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Response;
use App\Core\Request;
use App\Models\Settings;
use App\Helpers\AuthHelper;

header( 'Content-Type: application/json' );

class SettingsController
{
	public static function saveSettings(): array
	{
		try
		{
			$jwt = Request::cookie( 'auth_token' );
			$decode_jwt = AuthHelper::readJWT( $jwt );
			$userId = $decode_jwt[ 'user_id' ];
		}
		catch ( \Exception $e )
		{
			Response::setStatusBadRequest();
			return [ 'error' => $e->getMessage() ];
		}

		$data = Request::body();

		foreach ( $data as $key => $value )
		{
			if ( empty( $key ) || empty( $value ) )
			{
				Response::setStatusBadRequest();
				return [ 'error' => 'Please fill key-value pairs!' ];
			}

			Settings::set( $userId, $key, $value );
			Response::setStatusCreated();
		}

		return [
			'data' => []
		];

	}
}