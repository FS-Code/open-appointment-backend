<?php

namespace App\Controllers;
use App\Core\Request;
use App\Models\Service;

class ServiceController
{
    public function getServices()
    {
        $userId = Request::$user->getId();
        $services = Service::getServicesByUserId($userId);

        return $services;
    }
}
