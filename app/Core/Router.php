<?php

namespace App\Core;

class Router
{
    /**
     * @var array<string, array<string, callable|array>> $routes
    */
    protected static array $routes = [ 'get' => [], 'post' => [] ];

    private static array $prefixes = [];

    public static function get( string $path, callable|array $callback, array $middlewares = []  ): void
    {
        $path = self::makePath( $path );

        self::$routes[ 'get' ][ $path ] = [
            'middlewares' => $middlewares,
            'controller' => $callback
        ];
    }

    public static function post( string $path, callable|array $callback, array $middlewares = [] ): void
    {
        $path = self::makePath( $path );

        self::$routes[ 'post' ][ $path ] = [
            'middlewares' => $middlewares,
            'controller' => $callback
        ];
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

        $route = self::$routes[ $method ][ $path ] ?? false;

        if ( empty( $route[ 'controller' ] ) )
        {
            Response::setStatusNotFound();

            exit("404 Not Found!");
        }

        if ( ! empty( $route['middlewares'] ) ) {
            try {
                foreach ( $route['middlewares'] as $middleware ) {
                    call_user_func( [$middleware, 'index'] );
                }
            } catch (\Exception $e) {
                echo json_encode( [ 'error' => $e->getMessage() ] );
                return;
            }
        }
        
        echo json_encode( [ 'data' => call_user_func( $route[ 'controller' ] ) ] );
    }
}