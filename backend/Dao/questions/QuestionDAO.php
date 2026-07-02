<?php

namespace App\Dao\questions;

use App\model\questions\Question;
use App\util\Conexao;
use PDO;

class QuestionDAO {
    private PDO $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConexao();
    }

    public function criar(Question $question): int {
        $sql = "INSERT INTO questions (statement, type, lesson_id) VALUES (?, ?, ?)";

        $stm = $this->conexao->prepare($sql);
        $stm->execute([$question->getStatement(), $question->getType()->value, $question->getLesson()->getId()]);

        return (int) $this->conexao->lastInsertId();
    }

}