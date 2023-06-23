<?php

namespace App\Models;

use App\DB\Model;
use App\Core\DB;
use PDO;
use Exception;


class Service extends Model
{
    private int $id;
    private int $userId;
    private string $name;
    private string $location;
    private string $details;
    private int $duration;
    private int $businessHoursId;
    private int $bufferId;

    //ozu de foreign key oldugu ucun static eledim
    public static function delete(int $id): void
    {
        $db = DB::DB();
        $stmt = $db->prepare("SELECT buffer_id, business_hours_id FROM services WHERE id=?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if (!$row) {
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
            'user_id'           => [$this->getUserId(),          PDO::PARAM_INT],
            'name'              => [$this->getName(),            PDO::PARAM_STR],
            'location'          => [$this->getLocation(),        PDO::PARAM_STR],
            'details'           => [$this->getDetails(),         PDO::PARAM_STR],
            'duration'          => [$this->getDuration(),        PDO::PARAM_INT],
            'business_hours_id' => [$this->getBusinessHoursId(), PDO::PARAM_INT],
            'buffer_id'         => [$this->getBufferId(),        PDO::PARAM_INT],
        ];

        if (!isset($this->id))
            $this->insert($val);
        else
            $this->update($val);
    }

    public function setUserId(int $userId): Service
    {
        $this->userId = $userId;

        return $this;
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
    public function getUserId(): int
    {
        return $this->userId;
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
        $sql = "INSERT INTO services (user_id, name, location, details, duration, business_hours_id, buffer_id)
                VALUES (:user_id, :name, :location, :details, :duration, :business_hours_id, :buffer_id)";

        $this->setId(DB::exeSQL($sql, $val));
    }

    private function update(array $val): void
    {
        $sql = "UPDATE services
                SET user_id = :user_id, name = :name, location = :location, details = :details, duration = :duration,
                    business_hours_id = :business_hours_id, buffer_id = :buffer_id
                WHERE id = $this->id";

        DB::exeSQL($sql, $val);
    }

    /**
     * @throws Exception
     */
    private function checkDurationValidity(): void
    {
        if ($this->duration < 60)
            throw new Exception("Duration can't be shorter than a minute");
    }

    public static function getServicesByUserId(int $userId): array
    {
        $db = DB::DB();

        $stmt = $db->prepare("SELECT * FROM services WHERE user_id = :userId");
        $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
        $stmt->execute();

        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$services) {
            throw new Exception('No service found for this user');
        }

        return $services;
    }
}