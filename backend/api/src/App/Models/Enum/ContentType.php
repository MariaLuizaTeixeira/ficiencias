<?php

namespace App\Models\Enum;

enum ContentType: string {
    case IMAGE = 'image';
    case AUDIO = 'audio';
    case VIDEO = 'video';
    case TEXT = 'text';
}
