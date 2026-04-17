<?php

namespace repository;

class UserRepository
{
    private mixed $pdo;

    public function __construct() {
        $this->pdo = require __DIR__ . '/../../../database/config.php';
    }
    public function save(mixed $data): void {
        $stmt = $this->pdo->prepare
        ("
            INSERT INTO users 
            (full_name, email, nickname, gender, birth_date, phone_number, lives, streak)
            VALUES 
            (:full_name, :email, :nickname, :gender, :birth_date, :phone_number, 0, 0)
            RETURNING *
        ");

        $stmt->execute([
            ':full_name' => $data['full_name'],
            ':email' => $data['email'],
            ':lives' => $data['lives'],
            ':streak' => $data['streak'],
            ':nickname' => $data['nickname'],
            ':gender' => $data['gender'],
            ':birth_date' => $data['birth_date']
        ]);
    }
}