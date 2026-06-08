<?php

namespace controller\questions;

use Dao\questions\MultipleChoiceDAO;
use Dao\questions\QuestionDAO;
use Mapper\QuestionMapper;

class MultipleChoiceQuestionController {
    private MultipleChoiceDAO $multipleChoiceDAO;
    public QuestionDAO $questionDAO;
    private QuestionMapper $questionMapper;

    public function __construct() {
        $this->multipleChoiceDAO = new MultipleChoiceDAO();
        $this->questionDAO = new QuestionDAO();
        $this->questionMapper = new QuestionMapper();
    }

    public function criar(array $data): void {
        $question = $this->questionMapper->arrayToQuestion($data);
        $questionId = $this->questionDAO->criar($question);

        $multipleChoiceQuestion = $this->questionMapper->arrayToMultipleChoice($data);
        $this->multipleChoiceDAO->criar($multipleChoiceQuestion, $questionId);

    }
}