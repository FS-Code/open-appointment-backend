<?php

namespace App\Models;

use App\Core\DB;
use App\DB\Model;

class Service extends Model{

    public function delete(int $id) : void
    {
        $db = DB::DB();
        $stmt = $db
            ->prepare("DELETE FROM service WHERE id=?")
            ->execute([$id]);

        if(!$stmt->rowCount()){
            throw new \Exception('Service not found');
        }
    }
    
}