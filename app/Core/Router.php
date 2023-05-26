<?php

namespace App\Core;

class Router
{
    /**
     * @var array<string, array<string, callable|array>> $routes
    */
    protected static array $routes = [ 'get' => [], 'post' => [] ];

    private static array $prefixes = [];

    public static function get( string $path, callable|array $callback ): void
    {
        $path = self::makePath( $path );

        self::$routes[ 'get' ][ $path ] = $callback;
    }

    public static function post( string $path, callable|array $callback ): void
    {
        $path = self::makePath( $path );

        self::$routes[ 'post' ][ $path ] = $callback;
    }

    private static function makePath( $path ): string
    {
        $path = trim( $path, '/' );

        if ( ! self::$prefixes )
        {
            return $path;
        }

        $prefixes = implode( '/', self::$prefixes );

        if ( empty( $path ) )
        {
            return $prefixes;
        }

        return $prefixes . '/' . $path;
    }

    public static function group( string $prefix, callable|array $callback ): void
    {
        self::$prefixes[] = trim( $prefix, '/' );

        call_user_func( $callback );

        array_pop( self::$prefixes );
    }

    public static function resolve(): void
    {
        $path   = Request::path();
        $method = Request::method();

        $callback = self::$routes[ $method ][ $path ] ?? false;

        if ( $callback === false )
        {
            Response::setStatusNotFound();
            echo "404 Not Found!";
            exit;
        }

        echo json_encode( [ 'data' => call_user_func( $callback ) ] );
    }
}