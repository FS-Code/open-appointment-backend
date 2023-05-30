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

    public static function get(string $key , $default = null ): string|null
    {
        if(!isset($_GET[$key]) || trim( $_GET[$key] ) === "")
        {
            return $default;
        }      
        return $_GET[$key];
    }

    public static function post(string $key, $default = null ): string|null
    {
        if(!isset($_POST[$key]) || trim( $_POST[$key] ) === "")
        {
            return $default;
        }
        return $_POST[$key];
    }

    public static function cookie(string $key, $default = null ): string|null
    {
        if(!isset($_COOKIE[$key]) || trim( $_COOKIE[$key] ) === "")
        {
            return $default;
        } 
        return $_COOKIE[$key];
    }

    public static function has(string $method, string|array $keys): bool
    {
        $method=strtolower($method);

        if($method == 'get')
        {
            if(is_array($keys))
            {
                return self::keysExists($_GET, $keys);
            }
            elseif(is_null(self::get($keys)))
            {
                    return false;
                return true;
            }
        }
        elseif($method == 'post')
        {
            if(is_array($keys))
            {
                return self::keysExists($_POST, $keys);
            }
            elseif(is_null(self::post($keys)))
            {
                    return false;
                
                return true;
            }
        }
        return false;
    }

    public static function keysExists(array $method ,array $keys): bool
    {
        foreach($keys as $key)
        {
            if(!in_array($key,array_keys($method)))
            {
                return false;
            }
            return true;
        }
    }
}