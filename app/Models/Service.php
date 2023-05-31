<?php

namespace App\Models;

use App\DB\Model;
use App\Core\DB;
use PDO;
use Exception;

class Service extends Model {
    const MONDAY = 'monday';
    const TUESDAY = 'tuesday';
    const WEDNESDAY = 'wednesday';
    const THURSDAY = 'thursday';
    const FRIDAY = 'friday';
    const SATURDAY = 'saturday';
    const SUNDAY = 'sunday';

    private static $conn;

    private static function getConnection()
    {
        if (!isset(self::$conn)) {
            self::$conn = DB::DB();
        }
        return self::$conn;
    }

    public static function createService(string $name,
                                         string $location,
                                         string $details,
                                         int $duration,
                                         array $businessHours,
                                         int $bufferBefore = 0,
                                         int $bufferAfter = 0 ): int
    {

        self::checkDurationValidity($duration);

        $sql = "INSERT INTO service (name, location, details, duration, business_hours_id, buffer_id)
                VALUES (:name, :location, :details, :duration, :business_hours_id, :buffer_id)";

        $businessHoursID = self::createBusinessHours($businessHours);
        $bufferID = self::createBuffer($bufferBefore, $bufferAfter);

        return self::exeSQL($sql, [
            'name'              => [ $name,            PDO::PARAM_STR ],
            'location'          => [ $location,        PDO::PARAM_STR ],
            'details'           => [ $details,         PDO::PARAM_STR ],
            'duration'          => [ $duration,        PDO::PARAM_INT ],
            'business_hours_id' => [ $businessHoursID, PDO::PARAM_INT ],
            'buffer_id'         => [ $bufferID,        PDO::PARAM_INT ],
        ]);
    }

    private static function createBusinessHours(array $businessHours): int
    {
        $mondayID = self::createWeekDay($businessHours[self::MONDAY]);
        $tuesdayID = self::createWeekDay($businessHours[self::TUESDAY]);
        $wednesdayID = self::createWeekDay($businessHours[self::WEDNESDAY]);
        $thursdayID = self::createWeekDay($businessHours[self::THURSDAY]);
        $fridayID = self::createWeekDay($businessHours[self::FRIDAY]);
        $saturdayID = self::createWeekDay($businessHours[self::SATURDAY]);
        $sundayID = self::createWeekDay($businessHours[self::SUNDAY]);

        $sql = sprintf("INSERT INTO business_hours (%s, %s, %s, %s, %s, %s, %s)
                        VALUES (:%s, :%s, :%s, :%s, :%s, :%s, :%s)",

                        self::MONDAY, self::TUESDAY, self::WEDNESDAY, self::THURSDAY, self::FRIDAY, self::SATURDAY, self::SUNDAY,
                        self::MONDAY, self::TUESDAY, self::WEDNESDAY, self::THURSDAY, self::FRIDAY, self::SATURDAY, self::SUNDAY);

        return self::exeSQL($sql, [
            self::MONDAY    => self::createBusinessHour($mondayID),
            self::TUESDAY   => self::createBusinessHour($tuesdayID),
            self::WEDNESDAY => self::createBusinessHour($wednesdayID),
            self::THURSDAY  => self::createBusinessHour($thursdayID),
            self::FRIDAY    => self::createBusinessHour($fridayID),
            self::SATURDAY  => self::createBusinessHour($saturdayID),
            self::SUNDAY    => self::createBusinessHour($sundayID)
        ]);
    }

    private static function createBusinessHour(string | null $id): array
    {
        return [ $id, $id ? PDO::PARAM_INT : PDO::PARAM_NULL ];
    }

    private static function createWeekDay(array | null $period): int | null
    {
        if( $period === null ) return null;

        $startTime = $period['starts'];
        $endTime = $period['ends'];

        self::checkTimeValidity($startTime, $endTime);

        $sql = "INSERT INTO week_day (start_time, end_time)
                VALUES (:start_time, :end_time)";

		return self::exeSQL($sql, [
            'start_time' => [ $startTime, PDO::PARAM_STR ],
            'end_time'   => [ $endTime,   PDO::PARAM_STR ]
        ]);
    }

    private static function checkTimeValidity(string $startTime, string $endTime): void
    {
        if($endTime < $startTime)
            throw new Exception("End hours of work should be after start hours");
    }

    private static function checkDurationValidity(int $duration): void {
        if ($duration < 60)
            throw new Exception("Duration can't be shorter than a minute");
    }

    private static function createBuffer(int $beforeTime, int $afterTime): int
    {
        $sql = "INSERT INTO buffer (before_time, after_time)
                VALUES (:before_time, :after_time)";

        return self::exeSQL($sql, [
            'before_time' => [ $beforeTime, PDO::PARAM_INT ],
            'after_time'  => [ $afterTime,  PDO::PARAM_INT ]
        ]);
    }

    private static function exeSQL(string $sql, array $valuesNTypes): int
    {
        $stmt = self::getConnection()->prepare($sql);

        foreach ($valuesNTypes as $k => $v) {
            $stmt->bindValue(":$k", $v[0], $v[1]);
        }

		$stmt->execute();

		return self::getConnection()->lastInsertId();
    }
}
