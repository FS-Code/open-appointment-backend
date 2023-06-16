<?php

namespace App\Models;

use PDO;
use Exception;
use App\Core\DB;
use App\DB\Model;

class Appointment extends Model
{

    private int $id;
    private int $user_id;
    private int $service_id;
    private int $customer_id;
    private string $starts_at;
    private string $ends_at;
    private string $created_at;
    private string $updated_at;

    public function __construct(int|null $id = null  )
    {

        if ( ! empty( $id ) ) {
            $query = DB::DB()->prepare( "SELECT * FROM appointments WHERE id= :id" );
            $query->bindParam( ":id", $id, PDO::PARAM_INT );
            $query->execute();

            $appointment = $query->fetchObject();
           

            if ( !$appointment ) throw new Exception("Appointment not found");

            $this->id = $id;
            $this->user_id = $appointment->user_id;
            $this->service_id = $appointment->service_id;
            $this->customer_id = $appointment->customer_id;
            $this->starts_at = $appointment->starts_at;
            $this->ends_at = $appointment->ends_at;
            $this->created_at = $appointment->created_at;
            $this->updated_at = $appointment->updated_at;
        }

    }

    public function insert(array $val): void
    {
        $sql = "INSERT INTO appointments (user_id, service_id,customer_id, starts_at, ends_at, created_at, updated_at)
                VALUES (:user_id, :service_id,:customer_id, :starts_at, :ends_at, :created_at, :updated_at)";

    $query = DB::DB()->prepare($sql);

    $query->bindParam(':user_id', $val['user_id'], PDO::PARAM_INT);
    $query->bindParam(':service_id', $val['service_id'], PDO::PARAM_INT);
    $query->bindParam(':customer_id', $val['customer_id'], PDO::PARAM_INT);
    $query->bindParam(':starts_at', $val['starts_at'], PDO::PARAM_STR);
    $query->bindParam(':ends_at', $val['ends_at'], PDO::PARAM_STR);
    $query->bindParam(':created_at', $val['created_at'], PDO::PARAM_STR);
    $query->bindParam(':updated_at', $val['updated_at'], PDO::PARAM_STR);

    $query->execute();
    }


    public function update(array $val): void
{
    if (empty($this->id)) {
        throw new Exception("Cannot update appointment without an ID");
    }

    $sql = "UPDATE appointments SET user_id = :user_id, service_id = :service_id,customer_id = :customer_id, starts_at = :starts_at, 
            ends_at = :ends_at, created_at = :created_at, updated_at = :updated_at
            WHERE id = :id";

    $query = DB::DB()->prepare($sql);

    $query->bindParam(':user_id', $val['user_id'], PDO::PARAM_INT);
    $query->bindParam(':service_id', $val['service_id'], PDO::PARAM_INT);
    $query->bindParam(':customer_id', $val['customer_id'], PDO::PARAM_INT);
    $query->bindParam(':starts_at', $val['starts_at'], PDO::PARAM_STR);
    $query->bindParam(':ends_at', $val['ends_at'], PDO::PARAM_STR);
    $query->bindParam(':created_at', $val['created_at'], PDO::PARAM_STR);
    $query->bindParam(':updated_at', $val['updated_at'], PDO::PARAM_STR);
    $query->bindParam(':id', $this->id, PDO::PARAM_INT);

    $query->execute();
}

public function delete(): void
{
    if (empty($this->id)) {
        throw new Exception("Cannot delete appointment without an ID");
    }

    $query = DB::DB()->prepare("DELETE FROM appointments WHERE id = :id");
    $query->bindParam(':id', $this->id, PDO::PARAM_INT);
    $query->execute();
}


public function save(array $val): void
{
    if (!empty($this->id)) {
        $this->update($val);
    } else {
        $this->insert($val);
    }
}

}
