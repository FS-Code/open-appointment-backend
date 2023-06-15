<?php

use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\AuthController;

use App\Core\Router;


/*-----------initialize routes-----------*/

Router::get( '', [ HomeController::class, 'index' ] );

Router::post( 'test', [ HomeController::class, 'test' ] );
Router::get( 'data', [ HomeController::class, 'someData' ] );

Router::group( 'user', function () {
    Router::get( '', [ HomeController::class, 'index' ] );

    Router::get( 'new', [ HomeController::class, 'test' ] );
} );

Router::group( 'api', function() {
	Router::post( 'register', [ AuthController::class, 'register' ] );
    Router::post( 'login', [ AuthController::class, 'login' ] );
} );