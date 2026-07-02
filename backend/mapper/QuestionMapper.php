<?php

namespace App\mapper;
use App\model\enums\ContentType;
use App\model\enums\QuestionType;
use App\model\questions\MultipleChoiceQuestion;
use App\model\questions\OpenQuestion;
use App\model\questions\Question;

class QuestionMapper {
    public function arrayToQuestion(array $data): Question {
        $question = new Question();
        $question->setType(QuestionType::from($data['type']));
        $question->setLesson($data['lesson']);
        $question->setStatement($data['statement']);

        return $question;
    }
    public function arrayToOpen(array $data): OpenQuestion {
        $openQuestion = new OpenQuestion();
        $openQuestion->setCorrectAnswer($data['correct-answer']);
        $openQuestion->setContent($data['content']);
        $openQuestion->setContentType(ContentType::from($data['content-type']));

        return $openQuestion;
    }

    public function arrayToMultipleChoice(array $data): MultipleChoiceQuestion {
        $multipleChoiceQuestion = new MultipleChoiceQuestion();
        $multipleChoiceQuestion->setContent($data['content']);
        $multipleChoiceQuestion->setContentType(ContentType::from($data['content-type']));

        return $multipleChoiceQuestion;
    }

}