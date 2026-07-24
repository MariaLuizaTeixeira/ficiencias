<?php

namespace App\controller\questions;

use App\Dao\questions\AssociativeQuestionDAO;
use App\Dao\questions\QuestionDAO;
use App\mapper\QuestionMapper;

class AssociativeQuestionController {
    private AssociativeQuestionDAO $associativeQuestionDAO;
    private QuestionMapper $questionMapper;
    private QuestionDAO $questionDAO;

    public function __construct() {
        $this->associativeQuestionDAO = new AssociativeQuestionDAO();
        $this->questionMapper = new QuestionMapper();
        $this->questionDAO = new QuestionDAO();
    }

    public function criar(array $dados) {
        $question = $this->questionMapper->arrayToQuestion($dados);
        $questionId = $this->questionDAO->criar($question);

        $associativeQuestion = $this->questionMapper->arrayToAssociative($questionId, $dados);
        $this->associativeQuestionDAO->criar($dados, $questionId);


    }
}