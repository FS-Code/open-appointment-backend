<?php

namespace App\Core;

use Dotenv\Dotenv;

class Env
{
    public static array $db = [
        'dsn'  => '',
        'user' => '',
        'pass' => ''
    ];

	public static string $secret = '';

    public static function init(): void
    {
        $dotenv = Dotenv::createMutable( ROOT );

        $dotenv->load();

        //initialize database environment variables
        self::$db[ 'dsn' ]  = $_ENV[ 'DB_DSN' ] ?? '';
        self::$db[ 'user' ] = $_ENV[ 'DB_USER' ] ?? '';
        self::$db[ 'pass' ] = $_ENV[ 'DB_PASS' ] ?? '';
	    self::$secret = $_ENV[ 'SECRET_KEY' ] ?? '';
    }
}