<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\DB;
use App\Models\Buffer;
use App\Models\BusinessHours;
use App\Models\WeekDay;
use PDO;
use Exception;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Customer;


class AppointmentController
{
    public static function getAllTimeslots(): array
    {
        $serviceId = Request::post('service_id');
        $startFrom = Request::post('start_from');

        if (!isset($serviceId, $startFrom)) {
            Response::setStatusBadRequest();
            return ['error' => 'Invalid request parameters'];
        }

        $stmt = DB::DB()->prepare("SELECT * FROM services WHERE id = :serviceId LIMIT 1");
        $stmt->bindParam(':serviceId', $serviceId, PDO::PARAM_INT);
        $stmt->execute();

        $service = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$service) {
            Response::setStatusBadRequest();
            return ['error' => 'Service not found'];
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

        return ['dates' => $timeslots];
    }



    function getAllAppointments()
    {
        try {
            $appointments = Appointment::getAllAppointments();

            $responseData = [];
            $responseData['data'] = [
                'appointments' => []
            ];

            foreach ($appointments as $appointment) {

                $service = new Service($appointment->getServiceId());
                $customer = new Customer($appointment->getCustomerId());
                $buffer = new Buffer($service->getBufferId());
                $businesshours = new BusinessHours($service->getBusinessHoursId());
                $monday = new WeekDay($businesshours->getMondayId());
                $tuesday = new WeekDay($businesshours->getTuesdayId());
                $wednesday = new WeekDay($businesshours->getWednesdayId());
                $thursday = new WeekDay($businesshours->getThursdayId());
                $friday = new WeekDay($businesshours->getFridayId());

                $appointmentData = [
                    'id' => $appointment->getId(),
                    'service' => [
                        'id' => $service->getId(),
                        'name' => $service->getName(),
                        'location' => $service->getLocation(),
                        'details' => $service->getDetails(),
                        'duration' => $service->getDuration(),
                        'businessHours' => [
                            'monday' => [
                                'start' => $monday->getStartTime(),
                                'ends' => $monday->getEndTime(),
                            ],
                            'tuesday' => [
                                'start' => $tuesday->getStartTime(),
                                'ends' => $tuesday->getEndTime(),
                            ],
                            'wednesday' => [
                                'start' => $wednesday->getStartTime(),
                                'ends' => $wednesday->getEndTime(),
                            ],
                            'thursday' => [
                                'start' => $thursday->getStartTime(),
                                'ends' => $thursday->getEndTime(),
                            ],
                            'friday' => [
                                'start' => $friday->getStartTime(),
                                'ends' => $friday->getEndTime(),
                            ],
                            'saturday' => null,
                            'sunday' => null
                        ],

                        "buffer" => [
                            "before" => $buffer->getBeforeTime(),
                            "after" => $buffer->getBeforeTime(),
                        ]
                    ],
                    'customer' => [
                        'name' => $customer->getName(),
                        'email' => $customer->getEmail()
                    ],
                    'starts_at' => $appointment->getStartsAt(),
                    'ends_at' => $appointment->getEndsAt(),
                    'created_at' => $appointment->getCreatedAt()
                ];

                $responseData['data']['appointments'][] = $appointmentData;
            }

            Response::setStatusOk();
            return $responseData;
        } catch (Exception $e) {
            Response::setStatus(500);
            echo $e->getMessage();
        }
    }

}