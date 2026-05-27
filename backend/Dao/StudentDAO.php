<?php

namespace Dao;

use Model\Student;
use Util\Conexao;

class StudentDAO {
    private PDO $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConexao();
    }

    public function listar(): array {
        $sql = "SELECT * FROM students";
        $stm = $this->conexao->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }

    public function criar(Student $student): void {


        $sql = "INSERT INTO students (id, lives, streak, birth_date, nickname, gender, phone_number, profile_image_url) VALUES (?, ?, ?, ?, ?, ?)";

        $stm = $this->conexao->prepare($sql);
        $stm->execute([$student->getId(), $student->getLives(), $student->getStreak(), $student->getBirth(), $student->getNickname(), $student->getGender(), $student->getPhoneNumber(), $student->getProfilePicture()]);
    }

    public function deletar(int $id): void {
        $sql = "DELETE FROM students WHERE id = ?";

        $stm = $this->conexao->prepare($sql);
        $stm->execute([$id]);
    }
}