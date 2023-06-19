<?php

namespace App\Controllers;

use App\Models\Service;
use App\Core\Request;
use App\Core\Response;

class ServiceController
{
    public function deleteServices(Request $request, Response $response)
    {
        $userId = $request->user->id;
        $serviceIds = $request->getBody('ids', []);

        
        foreach ($serviceIds as $serviceId) {
            $service = Service::find($serviceId);

            if (!$service) {
                $response->json(['error' => 'Service not found.'], 404);
                return;
            }

            if ($service->getUserId() !== $userId) {
                $response->json(['error' => "You don't have permission."], 403);
                return;
            }

            $service->delete();
        }

        $response->json(['data' => []]);
    }
}