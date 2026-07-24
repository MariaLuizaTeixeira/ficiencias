<?php

namespace App\controller\questions;

use App\mapper\QuestionMapper;
use App\Dao\questions\MultipleChoiceDAO;
use App\Dao\questions\QuestionDAO;

class MultipleChoiceQuestionController {
    private MultipleChoiceDAO $multipleChoiceDAO;
    public QuestionDAO $questionDAO;
    private QuestionMapper $questionMapper;
    private OptionController $optionController;

    public function __construct() {
        $this->multipleChoiceDAO = new MultipleChoiceDAO();
        $this->questionDAO = new QuestionDAO();
        $this->questionMapper = new QuestionMapper();
        $this->optionController = new OptionController();
    }

    public function criar(array $data): void {
        $question = $this->questionMapper->arrayToQuestion($data);
        $questionId = $this->questionDAO->criar($question);

        $multipleChoiceQuestion = $this->questionMapper->arrayToMultipleChoice($data);
        $this->multipleChoiceDAO->criar($multipleChoiceQuestion, $questionId);

        $this->optionController->criar($data['opcoes'], $questionId);

    }
}