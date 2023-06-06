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

    public static function exeSQL(string $sql, array $valuesNTypes): int {
        $stmt = self::$pdo->prepare($sql);

        foreach ($valuesNTypes as $k => $v) {
            $stmt->bindValue(":$k", $v[0], $v[1]);
        }

        $stmt->execute();

        return self::$pdo->lastInsertId();
    }
}