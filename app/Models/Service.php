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

    public static function create(string $name,
                                  string $location,
                                  string $details,
                                  int $duration,
                                  int $businessHoursId,
                                  int $bufferId): self
    {
        $service = new Service();
        $service->setName($name);
        $service->setLocation($location);
        $service->setDetails($details);
        $service->setDuration($duration);
        $service->setBusinessHoursId($businessHoursId);
        $service->setBufferId($bufferId);
        $service->save();

        return $service;
    }

    public function save(): void
    {
        $sql = '';

        if ( !isset($this->id) )
            $sql = $this->insert();
        else
            $sql = $this->update();

        $this->setId(DB::exeSQL($sql, [
            'name'              => [ $this->getName(),            PDO::PARAM_STR ],
            'location'          => [ $this->getLocation(),        PDO::PARAM_STR ],
            'details'           => [ $this->getDetails(),         PDO::PARAM_STR ],
            'duration'          => [ $this->getDuration(),        PDO::PARAM_INT ],
            'business_hours_id' => [ $this->getBusinessHoursId(), PDO::PARAM_INT ],
            'buffer_id'         => [ $this->getBufferId(),        PDO::PARAM_INT ],
        ]));
    }

    private function insert(): string {
        return "INSERT INTO service (name, location, details, duration, business_hours_id, buffer_id)
        VALUES (:name, :location, :details, :duration, :business_hours_id, :buffer_id)";
    }

    private function update(): string {
        $id =  $this->id;
        return "UPDATE service
                SET name = :name, location = :location, details = :details, duration = :duration,
                    business_hours_id = :business_hours_id, buffer_id = :buffer_id
                WHERE id = $id";
    }

    private function checkDurationValidity(): void {
        if ($this->duration < 60)
            throw new Exception("Duration can't be shorter than a minute");
    }

    private function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setName(string $name): void {
        $this->name = $name;
    }
    public function setLocation(string $location): void {
        $this->location = $location;
    }
    public function setDetails(string $details): void {
        $this->details = $details;
    }
    public function setDuration(int $duration): void {
        $this->duration = $duration;
        $this->checkDurationValidity();
    }
    public function setBusinessHoursId(int $businessHoursId): void {
        $this->businessHoursId = $businessHoursId;
    }
    public function setBufferId(int $bufferId): void {
        $this->bufferId = $bufferId;
    }

    public function getName(): string {
        return $this->name;
    }
    public function getLocation(): string {
        return $this->location;
    }
    public function getDetails(): string {
        return $this->details;
    }
    public function getDuration(): int {
        return $this->duration;
    }
    public function getBusinessHoursId(): int {
        return $this->businessHoursId;
    }
    public function getBufferId(): int {
        return $this->bufferId;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
