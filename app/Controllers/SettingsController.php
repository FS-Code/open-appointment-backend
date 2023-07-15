<?php

namespace App\Controllers;

use App\Core\Response;
use App\Core\Request;
use App\Models\Settings;

class SettingsController
{
	public static function saveSettings(): array
	{
		$userId = Request::$user->getId();
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

		return [];

	}
}