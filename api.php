<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\SettingsController;
use App\Middlewares\AuthMiddleware;
use App\Controllers\AppointmentController;

use App\Core\Router;


/*-----------initialize routes-----------*/

Router::get( '', [ HomeController::class, 'index' ] );

Router::group( 'api', function() {
	Router::post( 'register', [ AuthController::class, 'register' ] );
    Router::post( 'login', [ AuthController::class, 'login' ] );
    Router::post( 'get-timeslots', [ AppointmentController::class, 'getAllTimeslots' ] );
	Router::post( 'save-settings', [ SettingsController::class, 'saveSettings' ], [ AuthMiddleware::class ] );

	Router::post( 'me', [ AuthController::class, 'me' ], [ AuthMiddleware::class ] );
} );
