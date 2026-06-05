<?php

namespace model\questions;

use Model\enums\ContentType;

class MultipleChoiceQuestion extends Question {
    private string $content;
    private ContentType $contentType;

    /** @var Option[] */
    private array $options = array();


    public function getContent(): string
    {
        return $this->content;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): void
    {
        $this->options = $options;
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
}