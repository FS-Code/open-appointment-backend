<?php

namespace App\Models;

use App\DB\Model;
use App\Core\DB;
use PDO;
use Exception;

class Buffer extends Model {
    private int $id;
    private int $beforeTime = 0;
    private int $afterTime = 0;

    public static function create(int $beforeTime = 0, int $afterTime = 0): self
    {
        $buffer = new Buffer();
        $buffer->setBeforeTime($beforeTime);
        $buffer->setAfterTime($afterTime);
        $buffer->save();

        return $buffer;
    }

    public function save(): void
    {
        $this->checkTimeValidity();

        $sql = '';

        if ( !isset($this->id) )
            $sql = $this->insert();
        else
            $sql = $this->update();

        $this->setId(DB::exeSQL($sql, [
            'before_time' => [ $this->beforeTime, PDO::PARAM_INT ],
            'after_time'  => [ $this->afterTime,  PDO::PARAM_INT ]
        ]));
    }

    private function insert(): string
    {
        return "INSERT INTO buffer (before_time, after_time)
                VALUES (:before_time, :after_time)";
    }

    private function update(): string
    {
        return "UPDATE buffer
                SET before_time = :before_time, after_time = :after_time
                WHERE id = $this->id";
    }

    private function checkTimeValidity()
    {
        if($this->beforeTime < 0 || $this->afterTime < 0)
            throw new Exception("\$beforeTime and \$afterTime can't be negative");
    }

    private function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setBeforeTime(int $beforeTime): void
    {
        $this->beforeTime = $beforeTime;
    }

    public function setAfterTime(int $afterTime): void
    {
        $this->afterTime = $afterTime;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBeforeTime(): int
    {
        return $this->beforeTime;
    }

    public function getAfterTime(): int
    {
        return $this->afterTime;
    }
}
