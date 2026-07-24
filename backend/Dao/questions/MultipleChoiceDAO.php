<?php

namespace App\Dao\questions;

use App\model\questions\MultipleChoiceQuestion;
use App\util\Conexao;
use PDO;

class MultipleChoiceDAO {
    private PDO $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConexao();
    }

    public function criar(MultipleChoiceQuestion $multipleChoiceQuestion, int $questionId): void {
        $sql = "INSERT INTO multiple_choice_questions (id, content, content_type) VALUES (?, ?, ?)";

        $stm = $this->conexao->prepare($sql);
        $stm->execute([$questionId, $multipleChoiceQuestion->getContent(), $multipleChoiceQuestion->getContentType()->value]);
    }

}