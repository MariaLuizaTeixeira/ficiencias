<?php

namespace App\model\enums;

enum QuestionType: string {
    case ASSOCIATION = 'association';
    case OPTIONS = 'options';
    case OPEN = 'open';
}
