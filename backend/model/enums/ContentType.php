<?php

namespace App\model\enums;

enum ContentType: string {
    case IMAGE = 'image';
    case AUDIO = 'audio';
    case VIDEO = 'video';
    case TEXT = 'text';
}
