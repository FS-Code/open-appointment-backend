<?php

namespace App\Models;

use App\Core\Env;
use PDO;
use App\Core\DB;
use App\DB\Model;

class Service extends Model
{
    public static  function createService(string $name, string $location, string $details, int $duration,
                                          array $businessHours, int $bufferBefore = 0, int $bufferAfter = 0): int
    {
        if($duration>60)
        {
            $businessHoursId = self::createBusinessHours($businessHours);
            $bufferId= self::createBuffer($bufferBefore,$bufferAfter);

            $data = [
                'name' => $name,
                'location' => $location,
                'details' => $details,
                'duration' => $duration,
                'businessHours_id' => $businessHoursId,
                'buffer_id' => $bufferId,
            ];

            $sql = "INSERT INTO `service` 
            (`name`,location ,details ,duration ,business_hours_id ,buffer_id )
            VALUES (:name, :location, :details, :duration, :businessHours_id, :buffer_id)";

            return self::run_sql($sql,$data);
        }
        return 0;
    }

    public static function createBusinessHours(array $businessHours): int
    {
        $monday     = is_array($businessHours['monday']) ? self::createWeekdays($businessHours['monday']['start_time'],$businessHours['monday']['end_time']) :null;
        $tuesday    = is_array($businessHours['tuesday']) ? self::createWeekdays($businessHours['tuesday']['start_time'],$businessHours['tuesday']['end_time']) :null;
        $wednesday  = is_array($businessHours['wednesday']) ? self::createWeekdays($businessHours['wednesday']['start_time'],$businessHours['wednesday']['end_time']) :null;
        $thursday   = is_array($businessHours['thursday']) ? self::createWeekdays($businessHours['thursday']['start_time'],$businessHours['thursday']['end_time']) :null;
        $friday     = is_array($businessHours['friday']) ? self::createWeekdays($businessHours['friday']['start_time'],$businessHours['friday']['end_time']) :null;
        $saturday   = is_array($businessHours['saturday']) ? self::createWeekdays($businessHours['saturday']['start_time'],$businessHours['saturday']['end_time']) :null;
        $sunday     = is_array($businessHours['sunday']) ? self::createWeekdays($businessHours['sunday']['start_time'],$businessHours['sunday']['end_time']) :null;
        $data = [
            'monday' => $monday,
            'tuesday' => $tuesday,
            'wednesday' => $wednesday,
            'thursday' => $thursday,
            'friday' => $friday,
            'saturday' => $saturday,
            'sunday' => $sunday,
        ];
        $sql = "INSERT INTO business_hours (monday, tuesday, wednesday,thursday,friday,saturday,sunday) VALUES (:monday, :tuesday, :wednesday, :thursday, :friday, :saturday,:sunday)";
        return self::run_sql($sql,$data);
    }

    public static function createWeekdays($start_time,$end_time): int
    {
        if ($start_time<$end_time){
            $data = [
                'start_time' => $start_time,
                'end_time' => $end_time,
            ];
            $sql = "INSERT INTO week_day (start_time, end_time) VALUES ( :start_time, :end_time)";
            return self::run_sql($sql,$data);
        }
        return 0;
    }

    public static function createBuffer($bufferBefore,$bufferAfter) :int
    {
        $data = [
            'before_time' => $bufferBefore,
            'after_time' => $bufferAfter,
        ];
        $sql = "INSERT INTO buffer (before_time, after_time) VALUES ( :before_time, :after_time)";
        return self::run_sql($sql,$data);
    }

    public static function run_sql($sql,$data)
    {
        try {

        $stmt= DB::DB()->prepare($sql);
        foreach ($data as $param => $value) {
            $stmt->bindValue(':' . $param, $value);
        }
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            echo "Data inserted successfully.";
        } else {
            echo "Error inserting data.";
        }
        return DB::DB()->lastInsertId();

        }catch(Exception $e) {
            echo "Unable to create";
        }

    }
}

