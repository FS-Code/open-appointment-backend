<?php

use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\AuthController;

use App\Models\Buffer;
use App\Models\BusinessHours;
use App\Models\Service;

use App\Core\Router;


/*-----------initialize routes-----------*/

Router::get('', [HomeController::class, 'index']);

Router::group('api', function () {
    Router::post('register', [AuthController::class, 'register']);
    Router::post('login', [AuthController::class, 'login']);
    Router::post('get-timeslots', [\App\Controllers\AppointmentController::class, 'getAllTimeslots']);

    Router::post('me', [AuthController::class, 'me'], [\App\Middlewares\AuthMiddleware::class]);

    Router::post('create-service', function (array $requestData) {
        // Extract the data from the request.
        $name = $requestData['name'];
        $location = $requestData['location'];
        $details = $requestData['details'];
        $duration = $requestData['duration'];
        $businessHours = $requestData['business_hours'];
        $buffer = $requestData['buffer'];
    
        $service = new Service();
        $service->setName($name);
        $service->setLocation($location);
        $service->setDetails($details);
        $service->setDuration($duration);
    
        $bufferInstance = new Buffer();
        $bufferId = $bufferInstance->getId(); 
    

        $businessHoursInstance = new BusinessHours();
        $businessHoursId = $BusinessHours->getId(); 
    
        $service->setBusinessHoursId($businessHoursId);
        $service->setBufferId($bufferId);
    
        $service->save();
    
        $serviceId = $service->getId();
    
        // Return the service ID as the response
        echo json_encode(['service_id' => $serviceId]);
    });
    
});

