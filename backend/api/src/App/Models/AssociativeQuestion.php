<?php

namespace App\Models;

use AllowDynamicProperties;
use App\Enum\QuestionType;

#[AllowDynamicProperties]
class AssociativeQuestion extends Question {
    // attributes
    private string $leftText;
    private string $rightMediaUrl;

    public function __construct(){
        $this->questionType = QuestionType::ASSOCIATION;
    }

    // getters and setters
    public function getLeftText(): string
    {
        return $this->leftText;
    }

    public function setLeftText(string $leftText): void
    {
        $this->leftText = $leftText;
    }

    public function getRightMediaUrl(): string
    {
        return $this->rightMediaUrl;
    }

    public function setRightMediaUrl(string $rightMediaUrl): void
    {
        $this->rightMediaUrl = $rightMediaUrl;
    }
}