<?php

namespace App\Dao\questions;

use App\model\questions\Option;
use App\util\Conexao;
use PDO;

class OptionDAO {
    private PDO $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConexao();
    }

    public function criar(Option $option): void {
        $sql = "INSERT INTO question_options (question_id, content, content_type, is_correct)
            VALUES (?, ?, ?, ?)";

        $stm = $this->conexao->prepare($sql);

        $stm->bindValue(1, $option->getQuestion()->getId(), PDO::PARAM_INT);
        $stm->bindValue(2, $option->getContent(), PDO::PARAM_STR);
        $stm->bindValue(3, $option->getContentType()->value, PDO::PARAM_STR);
        $stm->bindValue(4, $option->isCorrect(), PDO::PARAM_BOOL);

        $stm->execute();
    }
}