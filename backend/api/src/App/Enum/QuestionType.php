<?php

namespace App\Enum;

enum QuestionType: string {
    case ASSOCIATION = 'association';
    case OPTIONS = 'options';
    case OPEN = 'open';
}
