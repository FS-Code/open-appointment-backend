<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\DB;
use PDO;
use Exception;

class AppointmentController {

    public function getAllTimeslots() {
        $serviceId = Request::post('service_id');
        $startFrom = Request::post('start_from');

        if (!isset($serviceId, $startFrom)) {
            Response::setStatusBadRequest();
            return [ 'error' => 'Invalid request parameters' ];
        }

        $stmt = DB::DB()->prepare("SELECT * FROM services WHERE id = :serviceId LIMIT 1");
        $stmt->bindParam(':serviceId', $serviceId, PDO::PARAM_INT);
        $stmt->execute();

        $service = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$service) {
            Response::setStatusBadRequest();
            return [ 'error' => 'Service not found' ];
        }

        $businessHoursId = $service['business_hours_id'];
        $duration = $service['duration'];

        try {
            $businessHours = DB::DB()->query(
                "SELECT * FROM business_hours WHERE id = $businessHoursId LIMIT 1"
            )->fetch(PDO::FETCH_ASSOC);
            $timeslots = [];
            $startFromDateTime = new \DateTime($startFrom);
            $endOfMonthDateTime = (new \DateTime($startFrom))->modify('last day of this month');
        } catch (Exception $e) {
            Response::setStatusBadRequest();
            return ['error' => $e->getMessage()];
        }

        while ($startFromDateTime <= $endOfMonthDateTime) {
            $weekDay = strtolower($startFromDateTime->format('l'));

            if (isset($businessHours[$weekDay])) {
                $weekDayId = $businessHours[$weekDay];
                $weekDayHours = DB::DB()->query(
                    "SELECT * FROM week_days WHERE id = $weekDayId LIMIT 1"
                )->fetch(PDO::FETCH_ASSOC);

                if ($weekDayHours) {
                    $startDateTime = new \DateTime($weekDayHours['start_time']);
                    $endDateTime = new \DateTime($weekDayHours['end_time']);

                    while ($startDateTime < $endDateTime) {
                        $timeslot = [
                            'start_time' => $startDateTime->format('H:i'),
                            'end_time' => $startDateTime->modify("+$duration minutes")->format('H:i')
                        ];

                        if (!isset($timeslots[$startFromDateTime->format('Y-m-d')])) {
                            $timeslots[$startFromDateTime->format('Y-m-d')] = [];
                        }

                        $timeslots[$startFromDateTime->format('Y-m-d')][] = $timeslot;
                    }
                }
            }

            $startFromDateTime->modify('+1 day');
        }

        return [ 'dates' => $timeslots ];
    }
}
