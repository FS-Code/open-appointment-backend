<?php

namespace App\Models;

use App\DB\Model;
use App\Core\DB;
use PDO;
use Exception;

class Appointment extends Model{
    private int $id;
    private int $user_id;
    private int $service_id;
    private int $customer_id;
    private string $starts_at;
    private string $ends_at;

    /**
     * @throws Exception
     */

    public function __construct(int|null $id = null){
        if(!empty($id)){
            $query = DB::DB()->prepare("SELECT * FROM `appointments` WHERE id= :id");
            $query->bindParam(":id",$id,PDO::PARAM_INT);
            $query->execute();

            $appointment = $query->fetchObject();

            if(!$appointment) throw new Exception("Appointment not found");

            $this->id = $id;
            $this->user_id = $appointment->user_id;
            $this->service_id = $appointment->service_id;
            $this->customer_id = $appointment->customer_id;
            $this->starts_at = $appointment->starts_at;
            $this->ends_at = $appointment->ends_at;
        }
    }

    public function getId() : int{
        return $this->id;
    }

    public function getUserId() : int{
        return $this->user_id;
    }

    public function getServiceId() : int{
        return $this->service_id;
    }

    public function getCustomerId() : int{
        return $this->customer_id;
    }

    public function getStartDateTime() : string{
        return $this->starts_at;
    }

    public function getEndDateTime() : string{
        return $this->ends_at;
    }

    public function setId(int $id) : Appointment{
        $this->id = $id;

        return $this;
    }

    public function setUserId(int $user_id) : void{
        $this->user_id = $user_id;
    }

    public function setServiceId(int $service_id) : void{
        $this->service_id = $service_id;
    }

    public function setCustomerId(int $customer_id) : void{
        $this->customer_id = $customer_id;
    }

    public function setStartDateTime(string $starts_at) : void{
        $this->starts_at = $starts_at;
    }

    public function setEndDateTime(string $ends_at) : void{
        $this->ends_at = $ends_at;
    }

    public function save(): void{
        $values = [
            'user_id'     => [$this->getUserId(), PDO::PARAM_INT],
            'service_id'  => [$this->getServiceId(), PDO::PARAM_INT],
            'customer_id' => [$this->getCustomerId(), PDO::PARAM_INT],
            'starts_at'   => [$this->getStartDateTime(), PDO::PARAM_STR],
            'ends_at'     => [$this->getEndDateTime(), PDO::PARAM_STR]
        ];

        if(!isset($this->id))
            $this->insert($values);
        else
            $this->update($values);
    }

    private function insert(array $values) : void{
        $query = "INSERT INTO `appointments`(user_id, service_id, customer_id, starts_at, ends_at)
                  VALUES(:user_id, :service_id, :customer_id, :starts_at, :ends_at)";
    
        $this->setId(DB::exeSQL($query,$values));
    }

    private function update(array $values) : void{
        $query = "UPDATE `appointments`
                  SET user_id = :user_id, service_id = :service_id, customer_id = :customer_id, starts_at = :starts_at, ends_at = :ends_at
                  WHERE id = $this->id";
        
        DB::exeSQL($query, $values);
    }

    public function delete(int $id) : void{
        $db = DB::DB();
        $query = $db->prepare("SELECT service_id, customer_id FROM `appointments` WHERE id=?");
        $query->execute([$id]);
        $row = $query->fetch(PDO::FETCH_OBJ);

        if(!$row)
            throw new Exception("There is no Appointment");
        
        $db->prepare("DELETE FROM `appointments` WHERE id=?")->execute([$id]);

        // Customer modeli olmadigi ucun helelik query ile etmeyi qerara aldim
        $db->prepare("DELETE FROM `customers` WHERE id=?")->execute([$row->customer_id]);

        Service::delete($row->service_id);
    }
}

?>