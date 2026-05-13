<?php

namespace App\Models;

use App\Models\Enums\ContentType;

class MultipleChoiceQuestion extends Question {
    private string $content;
    private ContentType $contentType;


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
}