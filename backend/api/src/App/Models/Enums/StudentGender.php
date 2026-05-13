<?php

namespace App\Models\Enums;

enum StudentGender: string {
    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';
}
