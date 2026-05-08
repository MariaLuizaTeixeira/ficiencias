<?php

namespace App\Enum;

enum AdminLevelType: string {
    case EDITOR = 'editor'; // CRUD courses, sections, lessons and questions
    case MODERATOR = 'moderator'; // CRUD courses, sections, lessons and questions and can ban students/editors
}
