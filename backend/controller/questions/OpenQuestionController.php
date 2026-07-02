<?php

namespace App\controller\questions;
use App\Dao\questions\OpenQuestionDAO;
use App\Dao\questions\QuestionDAO;
use App\mapper\QuestionMapper;

class OpenQuestionController {
    private OpenQuestionDAO $openQuestionDAO;
    private QuestionMapper $questionMapper;
    private QuestionDAO $questionDAO;

    public function __construct() {
        $this->openQuestionDAO = new OpenQuestionDAO();
        $this->questionDAO = new QuestionDAO();
        $this->questionMapper = new QuestionMapper();
    }

    public function criar(array $data): void {
        $question = $this->questionMapper->arrayToQuestion($data);
        $questionId = $this->questionDAO->criar($question);

        $openQuestion = $this->questionMapper->arrayToOpen($data);
        $this->openQuestionDAO->criar($openQuestion, $questionId);
    }
}