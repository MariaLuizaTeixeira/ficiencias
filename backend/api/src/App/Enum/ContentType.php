<?php

namespace App\Enum;

enum ContentType: string {
    case IMAGE = 'image';
    case AUDIO = 'audio';
    case VIDEO = 'video';
    case TEXT = 'text';
}
