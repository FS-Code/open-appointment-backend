<?php

namespace App\Models;

use App\DB\Model;
use App\Core\DB;
use PDO;
use Exception;

class Customer extends Model {
    private ?int $id;
    private ?string $name;
    private ?string $email;
    private ?string $address;

    public function __construct(PDO $pdo, ?int $id = null) {
        parent::__construct($pdo);
        $this->id = $id;

        if ($id !== null) {
            $customer = $this->findByID($id);

            if ($customer) {
                $this->name = $customer['name'];
                $this->email = $customer['email'];
                $this->address = $customer['address'];
            }
        }
    }

    public function getID(): ?int {
        return $this->id;
    }

    public function setID(?int $id): void {
        $this->id = $id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(?string $name): void {
        $this->name = $name;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(?string $email): void {
        $this->email = $email;
    }

    public function getAddress(): ?string {
        return $this->address;
    }

    public function setAddress(?string $address): void {
        $this->address = $address;
    }

    public function save(): void {
        if ($this->id !== null) {
            $this->update();
        } else {
            $this->insert();
        }
    }

    public function update(): void {
        try {
            $stmt = $this->db->prepare('UPDATE customers SET name = :name, email = :email, address = :address WHERE id = :id');
            $stmt->execute([
                'name' => $this->name,
                'email' => $this->email,
                'address' => $this->address,
                'id' => $this->id
            ]);
        } catch (Exception $e) {
            // Handle the exception
        }
    }

    public function insert(): void {
        try {
            $stmt = $this->db->prepare('INSERT INTO customers (name, email, address) VALUES (:name, :email, :address)');
            $stmt->execute([
                'name' => $this->name,
                'email' => $this->email,
                'address' => $this->address
            ]);

            $this->id = $this->db->lastInsertId();
        } catch (Exception $e) {
            // Handle the exception
        }
    }

    public function delete(): void {
        if ($this->id !== null) {
            try {
                $stmt = $this->db->prepare('DELETE FROM customers WHERE id = :id');
                $stmt->execute(['id' => $this->id]);

                $this->id = null;
            } catch (Exception $e) {
                // Handle the exception
            }
        }
    }
}
