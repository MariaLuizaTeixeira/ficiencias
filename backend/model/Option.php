<?php

namespace Model;

use Model\enums\ContentType;

class Option {
    private int $id;
    private MultipleChoiceQuestion $question;
    private string $content;
    private ContentType $contentType;
    private bool $isCorrect;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getQuestion(): MultipleChoiceQuestion
    {
        return $this->question;
    }

    public function setQuestion(MultipleChoiceQuestion $question): void
    {
        $this->question = $question;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContentType(): ContentType
    {
        return $this->contentType;
    }

    public function setContentType(ContentType $contentType): void
    {
        $this->contentType = $contentType;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(bool $isCorrect): void
    {
        $this->isCorrect = $isCorrect;
    }
}