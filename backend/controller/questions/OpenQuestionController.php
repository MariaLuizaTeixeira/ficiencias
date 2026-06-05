<?php

namespace controller\questions;
use Dao\questions\OpenQuestionDao;
use Dao\questions\QuestionDAO;
use model\questions\OpenQuestion;

class OpenQuestionController {
    private OpenQuestionDAO $openQuestionDAO;
    private QuestionDAO $questionDAO;

    public function __construct() {
        $this->openQuestionDAO = new OpenQuestionDAO();
        $this->questionDAO = new QuestionDAO();
    }

    public function criar(OpenQuestion $openQuestion): void {
        $this->openQuestionDAO->criar($openQuestion);
        $this->questionDAO->criar($openQuestion);
    }
}