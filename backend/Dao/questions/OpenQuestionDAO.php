<?php

namespace App\Dao\questions;
use App\model\questions\OpenQuestion;
use PDO;
use App\util\Conexao;

class OpenQuestionDAO {
    private PDO $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConexao();
    }

    public function criar(OpenQuestion $openQuestion, int $questionId): void {
        $sql = "INSERT INTO open_questions (id, correct_answer, content, content_type) VALUES (?, ?, ?, ?)";

        $stm = $this->conexao->prepare($sql);
        $stm->execute([$questionId, $openQuestion->getCorrectAnswer(), $openQuestion->getContent(), $openQuestion->getContentType()->value]);
    }
}