<?php

namespace App\DB;

use App\Core\DB;
use PDOStatement;

class Model
{
    public static function prepare( $query, $args ): PDOStatement
    {
        return DB::prepare( $query, $args );
    }
}