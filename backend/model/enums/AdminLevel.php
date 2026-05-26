<?php

namespace Model\enums;

enum AdminLevel: string {
    case EDITOR = 'editor';
    case MODERATOR = 'moderator';
}
