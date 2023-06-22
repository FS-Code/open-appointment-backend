<?php

use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\AuthController;
use App\Controllers\ServiceController;
use App\Core\Router;


/*-----------initialize routes-----------*/

Router::get( '', [ HomeController::class, 'index' ] );

Router::group( 'api', function() {
	Router::post( 'register', [ AuthController::class, 'register' ] );
    Router::post( 'login', [ AuthController::class, 'login' ] );
    Router::post( 'get-timeslots', [ \App\Controllers\AppointmentController::class, 'getAllTimeslots' ] );
    Router::post('create-service',[ServiceController::class, 'create']);
    Router::post('me', [AuthController::class, 'me'], [\App\Middlewares\AuthMiddleware::class]);
} );
