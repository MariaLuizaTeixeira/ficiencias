<?php

namespace model\questions;

use model\divisions\Lesson;
use Model\enums\QuestionType;

abstract class Question {
    protected int $id;
    protected string $statement;
    protected QuestionType $type;
    protected Lesson $lesson;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getStatement(): string
    {
        return $this->statement;
    }

    public function setStatement(string $statement): void
    {
        $this->statement = $statement;
    }

    public function getType(): QuestionType
    {
        return $this->type;
    }

    public function setType(QuestionType $type): void
    {
        $this->type = $type;
    }

    public function getLesson(): Lesson
    {
        return $this->lesson;
    }

    public function setLesson(Lesson $lesson): void
    {
        $this->lesson = $lesson;
    }
}