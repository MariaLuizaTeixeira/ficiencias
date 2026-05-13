<?php

namespace App\Models;

class MultipleChoiceAnswer extends Answer{
    private Option $option;

    public function getOption(): Option
    {
        return $this->option;
    }

    public function setOption(Option $option): void
    {
        $this->option = $option;
    }
}