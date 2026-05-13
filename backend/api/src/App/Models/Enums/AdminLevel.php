<?php

namespace App\Models\Enums;

enum AdminLevel: string {
    case EDITOR = 'editor';
    case MODERATOR = 'moderator';
}
