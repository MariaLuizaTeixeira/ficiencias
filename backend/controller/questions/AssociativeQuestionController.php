<?php

namespace controller\questions;

use Controller\AssociativeQuestionDAO;

class AssociativeQuestionController {
    private AssociativeQuestionDAO $associativeQuestionDAO;

    public function __construct() {
        $this->associativeQuestionDAO = new AssociativeQuestionDAO();
    }

    public function criar(array $dados) {
        $this->associativeQuestionDAO->criar($dados);
    }
}