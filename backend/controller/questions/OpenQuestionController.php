<?php

namespace controller\questions;
use Dao\questions\OpenQuestionDao;
use Dao\questions\QuestionDAO;
use Mapper\QuestionMapper;

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