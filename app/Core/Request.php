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

    public static function get(string $key, $default = null): ?string {

        if (isset($_GET[$key]) && trim($_GET[$key])) {

            return $_GET[$key];

        }
        
        return $default;
    }

    public static function post(string $key, $default = null): ?string {

        if (isset($_POST[$key])  && trim($_GET[$key])) {

            return $_POST[$key];

        }
        
        return $default;
    }

    public static function cookie(string $key, $default = null): ?string {

        if (isset($_COOKIE[$key]) && trim($_COOKIE[$key])) {

            return $_COOKIE[$key];

        }
        
        return $default;
    }

    function has(string $type ,string|array $key): bool {

        $input_variables = $type === "POST" || $type === "post" ? $_POST : $_GET;

        if (is_array($key)) {
            foreach ($key as $i) {
                if (!isset($input_variables[$i])) {
                    return false;
                }
            }
            return true;
        } else {
            return isset($input_variables[$key]);
        }
    }
    
    
}