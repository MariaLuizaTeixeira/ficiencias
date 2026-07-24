<?php

namespace App\mapper;
use App\model\divisions\Lesson;
use App\model\enums\ContentType;
use App\model\enums\QuestionType;
use App\model\questions\MultipleChoiceQuestion;
use App\model\questions\OpenQuestion;
use App\model\questions\Option;
use App\model\questions\Question;

class QuestionMapper {
    public function arrayToQuestion(array $data): Question {
        $question = new Question();
        $question->setType(QuestionType::from($data['type']));
        $question->setStatement($data['statement']);

        $lesson = new Lesson();
        $lesson->setId((int)$data['lesson']);
        $question->setLesson($lesson);

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

    public function arrayToAssociative(int $questionId, array $dados) {
    }

    public function arrayToOption(array $opcao, int $questionId): Option {
        $option = new Option();
        $option->setContent($opcao['content']);
        $option->setContentType(ContentType::from($opcao['content-type']));
        $option->setIsCorrect($opcao['is-correct']);

        $question = new MultipleChoiceQuestion();
        $question->setId($questionId);
        $option->setQuestion($question);

        return $option;
    }

    public function arrayToAssociativeItem() {

    }

    public function arrayToAssociativePair() {

    }

}