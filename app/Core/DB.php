<?php

namespace App\Core;

use PDO;
use PDOStatement;

class DB
{
    private static PDO $pdo;

    public static function init(): void
    {
        self::$pdo = new PDO( Env::$db[ 'dsn' ], Env::$db[ 'user' ], Env::$db[ 'pass' ] );

        self::$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }

    public static function DB(): PDO
    {
        return self::$pdo;
    }
}