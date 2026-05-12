<?php

namespace App\Models;

use App\Models\Enum\ContentType;

class OptionsQuestion extends Question {
    private string $content;
    private ContentType $contentType;
}