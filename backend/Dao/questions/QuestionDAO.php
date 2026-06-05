<?php

namespace Dao\questions;

use Model\questions\Question;
use Util\Conexao;
use PDO;

class QuestionDAO {
    private PDO $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConexao();
    }

    public function criar(Question $question): void {
        $sql = "INSERT INTO questions (statement, type_id, lesson_id) VALUES (?, ?, ?)";

        $stm = $this->conexao->prepare($sql);
        $stm->execute([$question->getStatement(), $question->getType(), $question->getLesson()->getId()]);
    }

}