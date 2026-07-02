<?php

namespace Dao\roles;

use App\model\roles\Student;
use PDO;
use App\util\Conexao;

class StudentDAO {
    private PDO $conexao;
    private UserDAO $userDAO;

    public function __construct() {
        $this->conexao = Conexao::getConexao();
        $this->userDAO = new UserDAO();
    }

    public function listar(): array {
        $sql = "SELECT * FROM students";
        $stm = $this->conexao->prepare($sql);
        $stm->execute();
        return $stm->fetchAll();
    }

    public function criar(Student $student): void {


        $sql = "INSERT INTO students (lives, streak, birth_date, nickname, gender, phone_number, profile_image_url) 
                VALUES (?, ?, ?, ?, ?)";

        $stm = $this->conexao->prepare($sql);
        $stm->execute([$student->getLives(), $student->getStreak(), $student->getBirth(),
                       $student->getNickname(), $student->getGender(), $student->getPhoneNumber(),
                       $student->getProfilePicture()
        ]);

        $this->userDAO->criar($student);
    }

    public function deletar(int $id): void {
        $sql = "DELETE FROM students WHERE id = ?";

        $stm = $this->conexao->prepare($sql);
        $stm->execute([$id]);
    }
}