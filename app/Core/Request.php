<?php

namespace App\Core;

class Request
{
    private static array $query = [];

    private static array $body = [];

    public static function path(): string
    {
        $path   = $_SERVER[ 'REQUEST_URI' ] ?? '/';
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



    public static function get( string $key, $default = null ): string|null {
        if(isset($_GET[$key])) {
            return $default;
        }  else {
            return filter_var($_GET[$key], FILTER_SANITIZE_STRING);
        }  
    
    }

    public static function post( string $key, $default = null ): string|null {
        if(isset($_POST[$key])) {
            return $default;
        }  else {
            return filter_var($_POST[$key], FILTER_SANITIZE_STRING);
        }  
    }

    public static function cookie( string $key, $default = null ): string|null {
        if(isset($_COOKIE[$key])) {
            return $default;
        }  else {
            return filter_var($_COOKIE[$key], FILTER_SANITIZE_STRING);
        }  
    }

    public static function has( string $method, string|array $key): bool {
       if($method === 'GET') {
        return isset($_GET[$key]);
       } else if($method === 'POST'){
            return  isset($_POST[$key]);
       } else {
         return false;
       }
    }
}