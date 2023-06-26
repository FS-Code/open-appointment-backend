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
    private ?int $mondayId;
    private ?int $tuesdayId;
    private ?int $wednesdayId;
    private ?int $thursdayId;
    private ?int $fridayId;
    private ?int $saturdayId;
    private ?int $sundayId;

    public function __construct( int|null $id = null )
    {
        if ( ! empty( $id ) ) {
            $statement = DB::DB()->prepare( "SELECT * FROM business_hours WHERE id = :id" );
            $statement->bindParam( ':id', $id );
            $statement->execute();
            $result = $statement->fetch( \PDO::FETCH_OBJ );

            if ( $result ) {
                $this->id = $id;
                $this->mondayId = $result->monday;
                $this->tuesdayId = $result->tuesday;
                $this->wednesdayId = $result->wednesday;
                $this->thursdayId = $result->thursday;
                $this->fridayId = $result->friday;
                $this->saturdayId = $result->saturday;
                $this->sundayId = $result->sunday;
            }
        }
    }

    public static function create(?int $mondayId,
                                  ?int $tuesdayId,
                                  ?int $wednesdayId,
                                  ?int $thursdayId,
                                  ?int $fridayId,
                                  ?int $saturdayId,
                                  ?int $sundayId): self
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
        $val = [
            self::MONDAY    => $this->SQLDayField($this->getMondayId()),
            self::TUESDAY   => $this->SQLDayField($this->getTuesdayId()),
            self::WEDNESDAY => $this->SQLDayField($this->getWednesdayId()),
            self::THURSDAY  => $this->SQLDayField($this->getThursdayId()),
            self::FRIDAY    => $this->SQLDayField($this->getFridayId()),
            self::SATURDAY  => $this->SQLDayField($this->getSaturdayId()),
            self::SUNDAY    => $this->SQLDayField($this->getSundayId())
        ];

        if ( !isset($this->id) )
            $this->insert($val);
        else
            $this->update($val);

    }

    private function insert(array $val): void
    {
        $sql = sprintf(
            "INSERT INTO business_hours (%s, %s, %s, %s, %s, %s, %s)
             VALUES (:%s, :%s, :%s, :%s, :%s, :%s, :%s)",
                self::MONDAY, self::TUESDAY, self::WEDNESDAY, self::THURSDAY, self::FRIDAY, self::SATURDAY, self::SUNDAY,
                self::MONDAY, self::TUESDAY, self::WEDNESDAY, self::THURSDAY, self::FRIDAY, self::SATURDAY, self::SUNDAY);
    
        $this->setId(DB::exeSQL($sql, $val));
    }

    private function update(array $val): void
    {
        $sql = sprintf(
            "UPDATE business_hours 
             SET %s = :%s, %s = :%s, %s = :%s, %s = :%s, %s = :%s, %s = :%s, %s = :%s
             WHERE id = $this->id",
                self::MONDAY, self::MONDAY, self::TUESDAY, self::TUESDAY, self::WEDNESDAY, self::WEDNESDAY,
                self::THURSDAY, self::THURSDAY, self::FRIDAY, self::FRIDAY, self::SATURDAY, self::SATURDAY, self::SUNDAY, self::SUNDAY);
    
        DB::exeSQL($sql, $val);
    }

    private function SQLDayField(?int $id): array
    {
        return [ $id, $id ? PDO::PARAM_INT : PDO::PARAM_NULL ];
    }

    private function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setMondayId(?int $id): void
    {
        $this->mondayId = $id;
    }
    public function setTuesdayId(?int $id): void
    {
        $this->tuesdayId = $id;
    }
    public function setWednesdayId(?int $id): void
    {
        $this->wednesdayId = $id;
    }
    public function setThursdayId(?int $id): void
    {
        $this->thursdayId = $id;
    }
    public function setFridayId(?int $id): void
    {
        $this->fridayId = $id;
    }
    public function setSaturdayId(?int $id): void
    {
        $this->saturdayId = $id;
    }
    public function setSundayId(?int $id): void
    {
        $this->sundayId = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getMondayId(): ?int
    {
        return $this->mondayId;
    }
    public function getTuesdayId(): ?int
    {
        return $this->tuesdayId;
    }
    public function getWednesdayId(): ?int
    {
        return $this->wednesdayId;
    }
    public function getThursdayId(): ?int
    {
        return $this->thursdayId;
    }
    public function getFridayId(): ?int
    {
        return $this->fridayId;
    }
    public function getSaturdayId(): ?int
    {
        return $this->saturdayId;
    }
    public function getSundayId(): ?int
    {
        return $this->sundayId;
    }

    public static function delete(int $id) : void
    {
        $db = DB::DB();
        $db->prepare("DELETE FROM business_hours WHERE id=?")
            ->execute([$id]);
    }
}
