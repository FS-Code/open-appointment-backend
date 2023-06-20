<?php

namespace App\Controllers;

class ServiceController
{
    public function createService()
    {
        
        function createService(array $requestData) : int
        {
            $name = $requestData['name'];
            $location = $requestData['location'];
            $details = $requestData['details'];
            $duration = $requestData['duration'];
            $businessHours = $requestData['business_hours'];
            $buffer = $requestData['buffer'];

            $serviceId = insertService($name, $location, $details, $duration);

            insertBusinessHours($serviceId, $businessHours);

            insertBuffer($serviceId, $buffer);

            return $serviceId;
        }

        function insertService(string $name, string $location, string $details, int $duration) : int
        {
            $query = "INSERT INTO services (name, location, details, duration, user_id, business_hours_id, buffer_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = DB::DB()->prepare($query);
            $userId = 1;
            $businessHoursId = 1;
            $bufferId = 1;
            $stmt->execute([$name, $location, $details, $duration, $userId, $businessHoursId, $bufferId]);
            $lastInsertedId = DB::DB()->lastInsertId();

            return intval($lastInsertedId);
        }

        function insertBusinessHours(int $serviceId, array $businessHours) : void
        {
            $query = "INSERT INTO business_hours (monday, tuesday, wednesday, thursday, friday, saturday, sunday) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = DB::DB()->prepare($query);

            $monday = isset($businessHours['monday']) ? $businessHours['monday'] : null;
            $tuesday = isset($businessHours['tuesday']) ? $businessHours['tuesday'] : null;
            $wednesday = isset($businessHours['wednesday']) ? $businessHours['wednesday'] : null;
            $thursday = isset($businessHours['thursday']) ? $businessHours['thursday'] : null;
            $friday = isset($businessHours['friday']) ? $businessHours['friday'] : null;
            $saturday = isset($businessHours['saturday']) ? $businessHours['saturday'] : null;
            $sunday = isset($businessHours['sunday']) ? $businessHours['sunday'] : null;

            $stmt->execute([$monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday]);
        }

        function insertBuffer(int $serviceId, array $buffer) : void
        {
            $query = "INSERT INTO buffers (before_time, after_time) VALUES (?, ?)";
            $stmt = DB::DB()->prepare($query);
            $beforeTime = $buffer['before'];
            $afterTime = $buffer['after'];
            $stmt->execute([$beforeTime, $afterTime]);
        }

    }
}

?>