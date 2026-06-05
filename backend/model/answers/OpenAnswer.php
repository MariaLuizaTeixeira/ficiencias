<?php

namespace model\answers;

class OpenAnswer extends Answer {
    private string $textAnswer;

    public function getTextAnswer(): string
    {
        return $this->textAnswer;
    }

    public function setTextAnswer(string $textAnswer): void
    {
        $this->textAnswer = $textAnswer;
    }
}