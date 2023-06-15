<?php

namespace App\Helpers;

use Exception;
use App\Core\Env;

class AuthHelper
{
	/**
	 * @throws Exception
	 */
	public static function generateJWT ( string | array $data ) : string
	{

		if ( empty( $data ) )
		{
			throw new Exception( "Data can't be empty" );
		}

		if ( ! Env::$secret )
		{
			throw new Exception( "Private key is missing" );
		}

		if ( is_array( $data ) )
		{
			$data = json_encode( $data );
		}

		$header = json_encode( [ "alg" => "HS256", "typ" => "JWT", ] );

		$base64UrlHeader = self::base64UrlEncode( $header );
		$base64UrlPayload = self::base64UrlEncode( $data );

		$signature = hash_hmac( "sha256", $base64UrlHeader . "." . $base64UrlPayload, Env::$secret, true );
		$base64UrlSignature = self::base64UrlEncode( $signature );

		if ( ! $base64UrlHeader || ! $base64UrlPayload || ! $base64UrlSignature )
		{
			throw new Exception( "Something went wrong. JWT is not created!" );
		}

		return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
	}

	/**
	 * @description since base64_encode does not replace the given signs, we need to do it manually with the help of str_replace built-in function. Because we need those to be changed in the jwt.
	 */
	public static function base64UrlEncode( string $text ) : string
	{
		return str_replace(
			[ '+', '/', '=' ],
			[ '-', '_', '' ],
			base64_encode( $text )
		);
	}

    /**
     * @description since base64_decode does not replace the given signs, we need to do it manually with the help of str_replace built-in function. Because we need those to be changed in the jwt.
     */
    public static function base64UrlDecode( string $text ) : string
    {
        return base64_decode( str_replace(
            [ '-', '_' ],
            [ '+', '/' ],
            $text
        ) );
    }

    /**
     * @throws Exception
     */
    public static function readJWT (string $jwt ) : string | array {
        if ( ! Env::$secret )
        {
            throw new Exception( "Private key is missing" );
        }

        $parts = explode(".", $jwt);

        if (count($parts) !== 3) throw new Exception("Token is malformed");

        $payload = self::base64UrlDecode( $parts[1] );

        if (self::generateJWT($payload) !== $jwt) throw new Exception("Token is invalid");

        return json_decode($payload, true) ?? $payload;
    }
}