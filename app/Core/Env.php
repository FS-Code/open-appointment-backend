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

    public static array $smtp = [
        'host'       => '',
        'username'   => '',
        'password'   => '',
        'port'       => '',
        'from_email' => '',
        'from_name'  => ''
    ];

    public static string $secret = '';

    public static function init(): void
    {
        $dotenv = Dotenv::createMutable(ROOT);

        $dotenv->load();

        //initialize database environment variables
        self::$db['dsn']  = $_ENV['DB_DSN'] ?? '';
        self::$db['user'] = $_ENV['DB_USER'] ?? '';
        self::$db['pass'] = $_ENV['DB_PASS'] ?? '';

        // Initialize SMTP environment variables
        self::$smtp['host']       = $_ENV['SMTP_HOST'] ?? '';
        self::$smtp['username']   = $_ENV['SMTP_USERNAME'] ?? '';
        self::$smtp['password']   = $_ENV['SMTP_PASSWORD'] ?? '';
        self::$smtp['port']       = $_ENV['SMTP_PORT'] ?? '';
        self::$smtp['from_email'] = $_ENV['FROM_EMAIL'] ?? '';
        self::$smtp['from_name']  = $_ENV['FROM_NAME'] ?? '';

        self::$secret = $_ENV['SECRET_KEY'] ?? '';
    }
}
