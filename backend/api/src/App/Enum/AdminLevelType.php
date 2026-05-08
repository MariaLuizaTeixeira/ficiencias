<?php

namespace App\Enum;

enum AdminLevelType: string {
    case EDITOR = 'editor';
    case MODERATOR = 'moderator';
}
