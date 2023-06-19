<?php

use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\AuthController;
use App\Controllers\ServiceController;
use App\Middlewares\AuthMiddleware;


use App\Core\Router;


/*-----------initialize routes-----------*/

Router::get( '', [ HomeController::class, 'index' ] );

Router::group( 'api', function() {
	Router::post( 'register', [ AuthController::class, 'register' ] );
    Router::post( 'login', [ AuthController::class, 'login' ] );

    Router::post('me', [AuthController::class, 'me'], [\App\Middlewares\AuthMiddleware::class]);
} );




Router::group( 'api', function() {
    Router::post( '/login', [ AuthController::class, 'login' ] );
} );


Router::post('/delete', [ServiceController::class, 'deleteServices'])
    ->addMiddleware(AuthMiddleware::class);



?>
