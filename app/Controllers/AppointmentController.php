<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Service;
use App\Core\DB;
use PDO;
use Exception;

class AppointmentController {
    public static function getAllTimeslots(): array
    {
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



    public static function createAppointment(Request $request, Response $response)
    {
        try {
            $serviceId     = $request->post('service_id');
            $customerName  = $request->post('customer.name');
            $customerEmail = $request->post('customer.email');
            $startsAt      = $request->post('starts_at');

            $statement = DB::DB()->prepare("SELECT * FROM customers WHERE email = :email");
            $statement->bindParam(':email', $customerEmail);
            $statement->execute();
            $customer = $statement->fetch(\PDO::FETCH_ASSOC);

                if (!$customer)
                {
                     $customer = new Customer();
                     $customer->setName($customerName);
                     $customer->setEmail($customerEmail);
                     $customer->save();
                }

            $statement = DB::DB()->prepare("SELECT * FROM services WHERE id = :id");
            $statement->bindParam(':id', $id);
            $statement->execute();
            $service = $statement->fetch(PDO::FETCH_ASSOC);

            if (!$service)
            {
                throw new Exception('Service not found');
            }

            $appointment = new Appointment();
            $appointment->setUserId($customer->getId());
            $appointment->setServiceId($serviceId);
            $appointment->setCustomerId($customer->getId());
            $appointment->setStartDateTime($startsAt);
            $appointment->setEndDateTime('');
            $appointment->save();

            $response->setStatusCreated();
            $response->setData([
                'data' => [
                    'appointment_id' => $appointment->getId()
                ]
            ]);

            return $response;
        } catch (Exception $e) {
            $response->setStatusBadRequest();
            $response->setData([
                'error' => $e->getMessage()
            ]);
            return $response;
        }
    }


}
