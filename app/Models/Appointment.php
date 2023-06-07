<?php

namespace App\Models;

use App\DB\Model;
use App\Core\DB;
use PDO;
use Exception;

class Appointment extends Model {
  /**
 * Create a new appointment and insert it into the appointments table.
 *
 * @param int   $serviceId  The ID of the booked service.
 * @param array $customer   An array containing customer data: [name, email].
 * @param int   $startsAt   The timestamp when the service starts.
 *
 * @return int  The ID of the newly created appointment.
 * @throws Exception if the time slot is already busy.
 */
function createAppointment(int $serviceId, array $customer, int $startsAt): int
{
    // Calculate the endsAt timestamp by adding the service duration to startsAt.
    $serviceDuration = getServiceDuration($serviceId);
    $endsAt = $startsAt + $serviceDuration;

    // Check for overlapping appointments with the same service and timeslot.
    if (isTimeSlotBusy($serviceId, $startsAt, $endsAt)) {
        throw new Exception("This time slot is busy.");
    }

    // Insert the customer data into the customers table and retrieve the customer ID.
    $customerId = insertCustomer($customer);

    // Insert the appointment data into the appointments table and retrieve the appointment ID.
    $appointmentId = insertAppointment($serviceId, $customerId, $startsAt, $endsAt);

    return $appointmentId;
}

/**
 * Check if the specified time slot is already busy.
 *
 * @param int $serviceId  The ID of the service.
 * @param int $startsAt   The starting timestamp of the time slot.
 * @param int $endsAt     The ending timestamp of the time slot.
 *
 * @return bool  True if the time slot is busy, false otherwise.
 */
function isTimeSlotBusy(int $serviceId, int $startsAt, int $endsAt): bool
{
    // Implement your database query here to check for overlapping appointments.
    $query = "SELECT COUNT(*) FROM appointment WHERE service_id = ? AND starts_at < ? AND ends_at > ?";
    $count = DB::exeSQL($query, [$serviceId, $endsAt, $startsAt])->fetchColumn();

    return $count > 0;
}

/**
 * Insert customer data into the customers table and return the inserted customer ID.
 *
 * @param array $customer  An array containing customer data: [name, email].
 *
 * @return int  The ID of the inserted customer.
 */
function insertCustomer(array $customer): int
{
    // Implement your database query here to insert customer data.
    $query = "INSERT INTO customer (name, email) VALUES (?, ?)";
    DB::exeSQL($query, [$customer['name'], $customer['email']]);

    return DB::getLastInsertedId();
}

/**
 * Insert appointment data into the appointments table and return the inserted appointment ID.
 *
 * @param int $serviceId   The ID of the booked service.
 * @param int $customerId  The ID of the customer.
 * @param int $startsAt    The starting timestamp of the appointment.
 * @param int $endsAt      The ending timestamp of the appointment.
 *
 * @return int  The ID of the inserted appointment.
 */
function insertAppointment(int $serviceId, int $customerId, int $startsAt, int $endsAt): int
{
    // Implement your database query here to insert appointment data.
    $query = "INSERT INTO appointment (service_id, customer_id, starts_at, ends_at) VALUES (?, ?, ?, ?)";
    DB::exeSQL($query, [$serviceId, $customerId, $startsAt, $endsAt]);

    return DB::getLastInsertedId();
}

/**
 * Retrieve the duration of the service based on the provided service ID.
 *
 * @param int $serviceId  The ID of the service.
 *
 * @return int  The duration of the service in seconds.
 */
function getServiceDuration(int $serviceId): int
{
    $query = "SELECT duration FROM service WHERE id = ?";
    $duration = DB::exeSQL($query, [$serviceId])->fetchColumn();

    return $duration;
}

}
