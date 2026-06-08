<?php

namespace Mapper;
use model\questions\MultipleChoiceQuestion;
use model\questions\OpenQuestion;
use model\questions\Question;

class QuestionMapper {
    public function arrayToQuestion(array $data): Question {
        $question = new Question();
        $question->setType($data['type']);
        $question->setLesson($data['lesson']);
        $question->setStatement($data['statement']);

        return $question;
    }
    public function arrayToOpen(array $data): OpenQuestion {
        $openQuestion = new OpenQuestion();
        $openQuestion->setCorrectAnswer($data['correct-answer']);
        $openQuestion->setContent($data['content']);
        $openQuestion->setContentType($data['contentType']);

        return $openQuestion;
    }

    public function arrayToMultipleChoice(array $data): MultipleChoiceQuestion {
        $multipleChoiceQuestion = new MultipleChoiceQuestion();
        $multipleChoiceQuestion->setContent($data['content']);
        $multipleChoiceQuestion->setContentType($data['contentType']);

        return $multipleChoiceQuestion;
    }

}