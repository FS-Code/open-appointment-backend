<?php

namespace App\Core;

class Request
{
    private static array $query = [];

    private static array $body = [];

    public static function path(): string
    {
        $path     = $_SERVER[ 'REQUEST_URI' ] ?? '/';
        $position = strpos( $path, '?' );

        if ( $position === false )
        {
            return trim( $path, '/' );
        }

        return trim( substr( $path, 0, $position ), '/' );
    }

    public static function method(): string
    {
        return strtolower( $_SERVER[ 'REQUEST_METHOD' ] );
    }

    public static function query(): array
    {
        if ( !! self::$query )
        {
            return self::$query;
        }

        foreach ( $_GET as $k => $v )
        {
            self::$query[ $k ] = filter_input( INPUT_GET, $k, FILTER_SANITIZE_SPECIAL_CHARS );
        }

        return self::$query;
    }

    public static function body(): array
    {
        if ( !! self::$body )
        {
            return self::$body;
        }

        foreach ( $_POST as $k => $v )
        {
            self::$body[ $k ] = filter_input( INPUT_POST, $k, FILTER_SANITIZE_SPECIAL_CHARS );
        }

        return self::$body;
    }

    public static function rawBody(): string
    {
        return '';
    }

    public static function get(string $key, $default = null): ?string
    {
        return (isset($_GET[$key]) && trim($_GET[$key]) !== "") ? $_GET[$key] : $default;
    }

    public static function post(string $key, $default = null): ?string
    {
        return (isset($_POST[$key]) && trim($_POST[$key]) !== "") ? $_POST[$key] : $default;
    }

    public static function cookie(string $key, $default = null): ?string
    {
        return (isset($_COOKIE[$key]) && trim($_COOKIE[$key]) !== "") ? $_COOKIE[$key] : $default;
    }

    public static function has(string $type, $key): bool
    {
        $type = strtoupper($type);

        if (!in_array($type, ['GET', 'POST'])) {
            return false;
        }

        $global = ($type === 'GET') ? $_GET : $_POST;

        if (is_array($key)) {
            foreach ($key as $k) {
                if (!isset($global[$k])) {
                    return false;
                }
            }

            return true;
        }

        return isset($global[$key]);
    }
}
