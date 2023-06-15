<?php

namespace App\Models;

use App\DB\Model;
use App\Core\DB;
use PDO;
use Exception;

class Service extends Model {
    private int $id;
    private string $name;
    private string $location;
    private string $details;
    private int $duration;
    private int $businessHoursId;
    private int $bufferId;

    public function delete(int $id) : void
    {
        $db = DB::DB();
        $stmt = $db->prepare("SELECT buffer_id, business_hours_id FROM services WHERE id=?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if(!$row){
            throw new \Exception('Service not found');
        }

        $db->prepare("DELETE FROM services WHERE id=?")
            ->execute([$id]);

        Buffer::delete($row['buffer_id']);
        BusinessHours::delete($row['business_hours_id']);
    }

    public function save(): void
    {
        $val = [
            'name'              => [ $this->getName(),            PDO::PARAM_STR ],
            'location'          => [ $this->getLocation(),        PDO::PARAM_STR ],
            'details'           => [ $this->getDetails(),         PDO::PARAM_STR ],
            'duration'          => [ $this->getDuration(),        PDO::PARAM_INT ],
            'business_hours_id' => [ $this->getBusinessHoursId(), PDO::PARAM_INT ],
            'buffer_id'         => [ $this->getBufferId(),        PDO::PARAM_INT ],
        ];

        if ( !isset($this->id) )
            $this->insert($val);
        else
            $this->update($val);
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }
    public function setDetails(string $details): void
    {
        $this->details = $details;
    }
    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
        $this->checkDurationValidity();
    }
    public function setBusinessHoursId(int $businessHoursId): void
    {
        $this->businessHoursId = $businessHoursId;
    }
    public function setBufferId(int $bufferId): void
    {
        $this->bufferId = $bufferId;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getLocation(): string
    {
        return $this->location;
    }
    public function getDetails(): string
    {
        return $this->details;
    }
    public function getDuration(): int
    {
        return $this->duration;
    }
    public function getBusinessHoursId(): int
    {
        return $this->businessHoursId;
    }
    public function getBufferId(): int
    {
        return $this->bufferId;
    }

    private function setId(int $id): void
    {
        $this->id = $id;
    }

    private function insert(array $val): void
    {
        $sql = "INSERT INTO services (name, location, details, duration, business_hours_id, buffer_id)
                VALUES (:name, :location, :details, :duration, :business_hours_id, :buffer_id)";

        $this->setId(DB::exeSQL($sql, $val));
    }

    private function update(array $val): void
    {
        $sql = "UPDATE services
                SET name = :name, location = :location, details = :details, duration = :duration,
                    business_hours_id = :business_hours_id, buffer_id = :buffer_id
                WHERE id = $this->id";

        DB::exeSQL($sql, $val);
    }

    private function checkDurationValidity(): void
    {
        if ($this->duration < 60)
            throw new Exception("Duration can't be shorter than a minute");
    }
}
