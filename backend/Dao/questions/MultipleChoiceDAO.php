<?php

namespace Dao\questions;

use model\questions\MultipleChoiceQuestion;
use Util\Conexao;
use PDO;

class MultipleChoiceDAO {
    private PDO $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConexao();
    }

    public function criar(MultipleChoiceQuestion $multipleChoiceQuestion): void {
        $sql = "INSERT INTO multiple_choice_questions (content, content_type) VALUES (?, ?)";

        $stm = $this->conexao->prepare($sql);
        $stm->execute([$multipleChoiceQuestion->getContent(), $multipleChoiceQuestion->getContentType()]);
    }

}