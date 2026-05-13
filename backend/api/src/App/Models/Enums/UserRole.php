<?php

namespace App\Models\Enums;

enum UserRole: string {
    case STUDENT = 'student';
    case ADMIN = 'admin';
}
