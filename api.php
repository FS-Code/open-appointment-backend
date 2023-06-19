<?php

use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\AuthController;
use App\Controllers\ServiceController;
use App\Middleware\AuthMiddleware;


use App\Core\Router;


/*-----------initialize routes-----------*/

Router::get( '', [ HomeController::class, 'index' ] );

Router::group( 'api', function() {
	Router::post( 'register', [ AuthController::class, 'register' ] );
    Router::post( 'login', [ AuthController::class, 'login' ] );

    Router::post('me', [AuthController::class, 'me'], [\App\Middlewares\AuthMiddleware::class]);
} );
<<<<<<< HEAD



Router::group( 'api', function() {
    Router::post( '/login', [ AuthController::class, 'login' ] );
} );


Router::post('/delete', [ServiceController::class, 'deleteServices'])
    ->addMiddleware(AuthMiddleware::class);



=======
>>>>>>> fb4849cca81a2d1a02621053c91726800f87097a
