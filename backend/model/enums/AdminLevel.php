<?php

namespace App\model\enums;

enum AdminLevel: string {
    case EDITOR = 'editor';
    case MODERATOR = 'moderator';
}
