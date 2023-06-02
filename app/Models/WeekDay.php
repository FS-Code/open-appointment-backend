<?php

namespace App\Models;

use App\DB\Model;
use App\Core\DB;
use PDO;
use Exception;

class WeekDay extends Model {
    private int $id;
    private string $startTime;
    private string $endTime;

    public static function create(string $startTime, string $endTime): self
    {
        $weekday = new WeekDay();
        $weekday->setStartTime($startTime);
        $weekday->setEndTime($endTime);
        $weekday->save();

        return $weekday;
    }

    public function save(): void
    {
        $this->checkTimeValidity();

        $val = [
            'start_time' => [ $this->startTime, PDO::PARAM_STR ],
            'end_time'   => [ $this->endTime,   PDO::PARAM_STR ]
        ];

        if ( !isset($this->id) )
            $this->insert($val);
        else
            $this->update($val);
    }

    private function insert(array $val): void
    {
        $sql = "INSERT INTO week_day (start_time, end_time)
                VALUES (:start_time, :end_time)";

        $this->setId(DB::exeSQL($sql, $val));
    }

    private function update(array $val): void
    {
        $sql = "UPDATE week_day
                SET start_time = :start_time, end_time = :end_time
                WHERE id = $this->id";

        DB::exeSQL($sql, $val);
    }

    private function checkTimeValidity(): void
    {
        if($this->endTime < $this->startTime)
            throw new Exception("End hours of work should be after start hours");
    }

    private function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setStartTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }

    public function setEndTime(string $endTime): void
    {
        $this->endTime = $endTime;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStartTime(): string
    {
        return $this->startTime;
    }

    public function getEndTime(): string
    {
        return $this->endTime;
    }
}
