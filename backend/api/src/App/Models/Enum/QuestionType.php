<?php

namespace App\Models\Enum;

enum QuestionType: string {
    case ASSOCIATION = 'association';
    case OPTIONS = 'options';
    case OPEN = 'open';
}
