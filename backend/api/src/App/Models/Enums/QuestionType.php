<?php

namespace App\Models\Enums;

enum QuestionType: string {
    case ASSOCIATION = 'association';
    case OPTIONS = 'options';
    case OPEN = 'open';
}
