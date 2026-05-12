<?php

namespace App\Models\Enum;

enum GenderType: string {
    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';
}
