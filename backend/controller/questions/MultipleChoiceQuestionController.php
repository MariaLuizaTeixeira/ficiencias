<?php

namespace controller\questions;

use Dao\questions\MultipleChoiceDAO;
use Dao\questions\QuestionDAO;
use model\questions\MultipleChoiceQuestion;

class MultipleChoiceQuestionController {
    private MultipleChoiceDAO $multipleChoiceDAO;
    public QuestionDAO $questionDAO;

    public function __construct() {
        $this->multipleChoiceDAO = new MultipleChoiceDAO();
        $this->questionDAO = new QuestionDAO();
    }

    public function criar(MultipleChoiceQuestion $multipleChoiceQuestion): void {
        $this->multipleChoiceDAO->criar($multipleChoiceQuestion);
        $this->questionDAO->criar($multipleChoiceQuestion);

    }
}