<?php

namespace App\Controllers;

use App\Core\Response;
use App\Core\Request;
use App\Models\Service;
use App\Models\Buffer;
use App\Models\BusinessHours;
use Exception;

class ServiceController
{
    public static function create(): array
    {
        try {
            $userId = Request::$user->getId(); // Assuming we have a $user property in our Request object.
            $name = Request::post('name');
            $location = Request::post('location');
            $details = Request::post('details');
            $duration = Request::post('duration');
            $businessHours = Request::post('business_hours'); // This now accepts an array
            $buffer = Request::post('buffer');

            if (empty($userId) || empty($name) || empty($location) || empty($duration) || empty($businessHours) || empty($buffer)) {
                throw new Exception("Required field(s) are missing");
            }

            // Create a Buffer
            $buffer = Buffer::create($buffer['before'], $buffer['after']);

            // Create BusinessHours
            $businessHours = BusinessHours::create(
                $businessHours[BusinessHours::MONDAY],
                $businessHours[BusinessHours::TUESDAY],
                $businessHours[BusinessHours::WEDNESDAY],
                $businessHours[BusinessHours::THURSDAY],
                $businessHours[BusinessHours::FRIDAY],
                $businessHours[BusinessHours::SATURDAY],
                $businessHours[BusinessHours::SUNDAY]
            );

            $service = new Service();
            $service->setUserId($userId)
                    ->setName($name)
                    ->setLocation($location)
                    ->setDetails($details)
                    ->setDuration($duration)
                    ->setBusinessHoursId($businessHours->getId())
                    ->setBufferId($buffer->getId());

            $service->save();
            return ['data' => ['id' => $service->getId()]];
        }
         catch (Exception $e) {
            Response::setStatusBadRequest();

            return ['error' => $e->getMessage()];
       }
    }
}
