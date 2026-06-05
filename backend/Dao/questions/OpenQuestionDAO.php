<?php

namespace Dao\questions;
use model\questions\OpenQuestion;
use PDO;
use Util\Conexao;

class OpenQuestionDAO {
    private PDO $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConexao();
    }

    public function criar(OpenQuestion $openQuestion): void {
        $sql = "INSERT INTO open_questions (correct_answer, content, content_type) VALUES (?, ?, ?)";

        $stm = $this->conexao->prepare($sql);
        $stm->execute([$openQuestion->getCorrectAnswer(), $openQuestion->getContent(), $openQuestion->getContentType()]);
    }
}