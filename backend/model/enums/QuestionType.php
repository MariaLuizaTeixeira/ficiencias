<?php

namespace Model\enums;

enum QuestionType: string {
    case ASSOCIATION = 'association';
    case OPTIONS = 'options';
    case OPEN = 'open';
}
