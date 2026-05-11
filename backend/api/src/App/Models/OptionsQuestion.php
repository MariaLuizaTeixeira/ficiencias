<?php

namespace App\Models;

use App\Enum\ContentType;

class OptionsQuestion extends Question {
    private string $content;
    private ContentType $contentType;
}