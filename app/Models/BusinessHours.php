<?php

namespace App\Models;

use App\DB\Model;
use App\Core\DB;
use PDO;


class BusinessHours extends Model {
    const MONDAY = 'monday';
    const TUESDAY = 'tuesday';
    const WEDNESDAY = 'wednesday';
    const THURSDAY = 'thursday';
    const FRIDAY = 'friday';
    const SATURDAY = 'saturday';
    const SUNDAY = 'sunday';

    private int $id;
    private int $mondayId;
    private int $tuesdayId;
    private int $wednesdayId;
    private int $thursdayId;
    private int $fridayId;
    private int $saturdayId;
    private int $sundayId;

    public static function create(int | null $mondayId,
                                  int | null $tuesdayId,
                                  int | null $wednesdayId,
                                  int | null $thursdayId,
                                  int | null $fridayId,
                                  int | null $saturdayId,
                                  int | null $sundayId): self
    {
        $businessHours = new BusinessHours();
        $businessHours->setMondayId($mondayId);
        $businessHours->setTuesdayId($tuesdayId);
        $businessHours->setWednesdayId($wednesdayId);
        $businessHours->setThursdayId($thursdayId);
        $businessHours->setFridayId($fridayId);
        $businessHours->setSaturdayId($saturdayId);
        $businessHours->setSundayId($sundayId);
        $businessHours->save();

        return $businessHours;
    }

    public function save(): void
    {
        $sql = sprintf("INSERT INTO business_hours (%s, %s, %s, %s, %s, %s, %s)
                        VALUES (:%s, :%s, :%s, :%s, :%s, :%s, :%s)",

        self::MONDAY, self::TUESDAY, self::WEDNESDAY, self::THURSDAY, self::FRIDAY, self::SATURDAY, self::SUNDAY,
        self::MONDAY, self::TUESDAY, self::WEDNESDAY, self::THURSDAY, self::FRIDAY, self::SATURDAY, self::SUNDAY);

        $this->setId(DB::exeSQL($sql, [
            self::MONDAY    => $this->SQLDayField($this->getMondayId()),
            self::TUESDAY   => $this->SQLDayField($this->getTuesdayId()),
            self::WEDNESDAY => $this->SQLDayField($this->getWednesdayId()),
            self::THURSDAY  => $this->SQLDayField($this->getThursdayId()),
            self::FRIDAY    => $this->SQLDayField($this->getFridayId()),
            self::SATURDAY  => $this->SQLDayField($this->getSaturdayId()),
            self::SUNDAY    => $this->SQLDayField($this->getSundayId())
        ]));
    }

    private function SQLDayField(int | null $id): array
    {
        return [ $id, $id ? PDO::PARAM_INT : PDO::PARAM_NULL ];
    }

    private function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setMondayId(int | null $id): void
    {
        $this->mondayId = $id;
    }
    public function setTuesdayId(int | null $id): void
    {
        $this->tuesdayId = $id;
    }
    public function setWednesdayId(int | null $id): void
    {
        $this->wednesdayId = $id;
    }
    public function setThursdayId(int | null $id): void
    {
        $this->thursdayId = $id;
    }
    public function setFridayId(int | null $id): void
    {
        $this->fridayId = $id;
    }
    public function setSaturdayId(int | null $id): void
    {
        $this->saturdayId = $id;
    }
    public function setSundayId(int | null $id): void
    {
        $this->sundayId = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getMondayId(): int
    {
        return $this->mondayId;
    }
    public function getTuesdayId(): int
    {
        return $this->tuesdayId;
    }
    public function getWednesdayId(): int
    {
        return $this->wednesdayId;
    }
    public function getThursdayId(): int
    {
        return $this->thursdayId;
    }
    public function getFridayId(): int
    {
        return $this->fridayId;
    }
    public function getSaturdayId(): int
    {
        return $this->saturdayId;
    }
    public function getSundayId(): int
    {
        return $this->sundayId;
    }
}
